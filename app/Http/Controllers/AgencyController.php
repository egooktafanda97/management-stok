<?php

namespace App\Http\Controllers;

use App\Services\AgencyService;
use Illuminate\Http\Request;
use TaliumAttributes\Collection\Controller\Controllers;
use TaliumAttributes\Collection\Controller\RestController;
use TaliumAttributes\Collection\Rutes\Group;
use TaliumAttributes\Collection\Rutes\Post;

#[RestController()]
#[Group(prefix: 'agency')]
class AgencyController extends Controller
{
    public function __construct(
        public AgencyService $agencyService
    ) {
    }
    #[Post("oncard-install")]
    #[RestController()]
    public function installWithOncard(Request $request)
    {
        try {
            $apiKey = $request->header("api-key");
            if ($apiKey != env("ONCARDAPP"))
                throw new \Exception("anda bukan oncard", 500);
            $result =  $this->agencyService->create([
                "oncard_instansi_id" => $request->oncard_instansi_id,
                "kode_instansi" => $request->kode_instansi,
                "apikeys" => $request->apikeys,
                "nama" => $request->nama,
                "username" => $request->username,
                "password" => $request->password,
                "alamat" => $request->alamat ?? null,
            ]);
            return response()->json($result, 200);
        } catch (\Throwable $th) {
            return response()->json($th->getMessage(), 500);
        }
    }
}
