<?php

namespace App\Http\Controllers;

use App\Constant\Status;
use App\Models\User;
use App\Services\GudangService;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use TaliumAttributes\Collection\Controller\Controllers;
use TaliumAttributes\Collection\Rutes\Get;
use TaliumAttributes\Collection\Rutes\Group;
use TaliumAttributes\Collection\Rutes\Post;

#[Controllers()]
#[Group(prefix: 'gudang', name: "gudang", middleware: ['web', 'auth', 'role:agency'])]
class GudangController extends Controller
{

    public function __construct(
        public GudangService $gudangService
    ) {
    }

    #[Get("")]
    public function index()
    {
        return view('Page.Gudang.index', [
            'gudang' => $this->gudangService->getPaginate(10)
        ]);
    }

    #[Get("create")]
    public function create()
    {
        return view('Page.Gudang.create');
    }

    #[Post("simpan")]
    public function store(Request $request)
    {
        try {
            if ($request->id) {
                $this->gudangService->update($request->id, [
                    "username" => $request->username,
                    "password" => $request->password,
                    "nama" => $request->nama,
                    "alamat" => $request->alamat,
                    "telepon" => $request->telepon,
                    "deskripsi" => $request->deskripsi,
                ]);
                return redirect()->route('gudang.index');
            }
            $this->gudangService->create([
                "username" => $request->username,
                "password" => $request->password,
                "nama" => $request->nama,
                "alamat" => $request->alamat,
                "telepon" => $request->telepon,
                "deskripsi" => $request->deskripsi,
            ]);
            Alert::success('Berhasil', 'Berhasil menambahkan gudang');
            return redirect()->route('gudang.index');
        } catch (\Throwable $th) {
            Alert::error('Gagal', 'Gagal menambahkan gudang');
            return redirect()->back();
        }
    }

    // udpate
    #[Get("edit/{id}")]
    public function edit($id)
    {
        $gudang = $this->gudangService->find($id);
        return view('Page.Gudang.create', compact('gudang'));
    }

    #[Post("update/{id}")]
    public function update(Request $request, $id)
    {
        try {
            $this->gudangService->update($id, [
                "nama" => $request->nama,
                "alamat" => $request->alamat,
                "telepon" => $request->telepon,
                "deskripsi" => $request->deskripsi,
            ]);
            return redirect()->route('gudang.index');
        } catch (\Throwable $th) {
            return response()->json([
                "msg" => 'gagal mengubah gudang'
            ], 500);
        }
    }

    // delete
    #[Get("delete/{id}")]
    public function delete($id)
    {
        try {
            $this->gudangService->update($id, [
                "status" => Status::INACTIVE
            ]);
            User::find($this->gudangService->find($id)->user_id)->update([
                "status" => Status::INACTIVE
            ]);
            return redirect()->route('gudang.index');
        } catch (\Throwable $th) {
            return response()->json([
                "msg" => 'gagal menghapus gudang'
            ], 500);
        }
    }

    public function find($id)
    {
        return $this->gudangService->find($id);
    }
}
