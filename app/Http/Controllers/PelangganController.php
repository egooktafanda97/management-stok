<?php

namespace App\Http\Controllers;

use App\Constant\UserType;
use App\Dtos\GeneralActorDTOs;
use App\Dtos\UsersDTOs;
use App\Models\Keyvendorpayment;
use Illuminate\Http\Request;
use App\Models\Pelanggan;
use App\Services\ActorService;
use App\Services\GeneralActorService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use RealRashid\SweetAlert\Facades\Alert;
use TaliumAttributes\Collection\Controller\Controllers;
use TaliumAttributes\Collection\Controller\RestController;
use TaliumAttributes\Collection\Rutes\Get;
use TaliumAttributes\Collection\Rutes\Name;
use TaliumAttributes\Collection\Rutes\Group;
use TaliumAttributes\Collection\Rutes\Post;

#[Controllers()]
#[Group(prefix: 'pelanggan', middleware: ['web',  'auth:web', 'role:gudang'])]
class PelangganController extends Controller
{
    public function __construct(
        public ActorService $actorService,
        public GeneralActorService $generalActorService
    ) {
    }

    #[Get("")]
    public function index()
    {
        return view('Page.Pelanggan.index', [
            'pelanggans' => $this->generalActorService->getPelanggan()
        ]);
    }


    #[Get("tambah")]
    public function formtambah()
    {
        return view('Page.Pelanggan.tambah');
    }

    /**
     * Store a newly created resource in storage.
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    #[Get("edit/{id}")]
    public function editForm($id)
    {
        $pelanggan = $this->generalActorService->getPelangganById($id);
        if (!$pelanggan) {
            return redirect()->route('pelanggan.index')->withErrors(['Pelanggan tidak ditemukan.']);
        }
        return view('Page.Pelanggan.edit', compact('pelanggan'));
    }


    #[Post("store/{id?}")]
    public function store(Request $request, $id = null)
    {
        try {
            $this->generalActorService->fromDTOs((
                    new GeneralActorDTOs(
                        oncard_instansi_id: $this->actorService->agency()->oncard_instansi_id ?? 1,
                        oncard_user_id: $request->oncard_user_id,
                        oncard_account_number: $request->oncard_account_number,
                        nama: $request->nama,
                        user_type: $request->user_type,
                        sync_date: Carbon::now()->format("Y-m-d"),
                        detail: $request->detail,
                        user: new UsersDTOs(
                            username: $request->username,
                            password: $request->password,
                        )
                    )
                )->setId($id ?? null)
            )->create();
            Alert::success('Success', 'Pelanggan berhasil ditambahkan!');
            return redirect()->route('pelanggan.index');
        } catch (\Throwable $th) {
            Alert::error('Error', 'Terjadi kesalahan saat menambahkan pelanggan!');
            return redirect()->back();
        }
    }



    // delete
    #[Get("delete/{id}")]
    public function delete($id)
    {
        $pelanggan = $this->generalActorService->getPelangganById($id);
        if (!$pelanggan) {
            return Redirect::back()->withErrors(['Pelanggan tidak ditemukan.']);
        }
        $pelanggan->delete();
        Alert::success('Success', 'Pelanggan berhasil dihapus!');
        return redirect()->back();
    }

    #[Get("search")]
    #[RestController(middleware: ['auth:api'])]
    public function search(Request $request)
    {
        $search = request('search');
        $search = $this->generalActorService->searchGeneralActor($search);
        return response()->json($search, 200);
    }

    #[Get("search/nopaginate")]
    #[RestController(middleware: ['auth:api'])]
    public function searchNopaginate(Request $request)
    {

        $search = request('q');
        $cardSerach = (bool) request("card") == "false" || request("card") == false ? false : true;
        $querySearch = $this->generalActorService->searchGeneralActorNopaginate($search, $cardSerach);
        if (count($querySearch) == 0 && !empty($cardSerach) && $cardSerach == true && strlen($search) >= 5) {
            $oncard = env("ONCARDURLAPP");
            $apiKey = Keyvendorpayment::where("app", 'oncard')->where("gudang_id", $this->actorService->gudang()->id)->first();
            $requestUsers = Http::withHeaders([
                "api-key" => $apiKey->apikeys
            ])
                ->get($oncard . "api/v1/oncard/users?search=$search&searchBy=card")
                ->throw();
            if ($requestUsers->status() == 200) {
                $result = $requestUsers->json()['data'];
                if (count($result)  > 0) {
                    $result = $result[0];
                    $this->generalActorService->fromDTOs(new GeneralActorDTOs(
                        oncard_instansi_id: $result['oncard_instansi_id'],
                        oncard_user_id: $result['oncard_user_id'],
                        oncard_account_number: $result['oncard_account_number'],
                        nama: $result['nama'],
                        user_type: UserType::General,
                        sync_date: Carbon::now()->format("Y-m-d"),
                        detail: "user by oncard",
                        card_hash: $result['card_hash'],
                        user: new UsersDTOs(
                            username: $result['card_hash'],
                            password: $result['card_hash'],
                        )
                    ))->create();
                    $querySearch = $this->generalActorService->searchGeneralActorNopaginate($search, $cardSerach);
                }
            }
        }
        return response()->json($querySearch, 200);
    }
}
