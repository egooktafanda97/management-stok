<?php

namespace App\Http\Controllers;

use App\Dtos\DebtUsersDTOs;
use App\Models\PaymentType;
use App\Services\DebtUserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use TaliumAttributes\Collection\Controller\Controllers;
use TaliumAttributes\Collection\Controller\RestController;
use TaliumAttributes\Collection\Rutes\Get;
use TaliumAttributes\Collection\Rutes\Group;
use TaliumAttributes\Collection\Rutes\Post;

#[Controllers()]
#[Group(prefix: 'payment', middleware: ['web',  'auth:web', 'role:kasir,gudang'])]
class PaymentController extends Controller
{
    public function __construct(
        private DebtUserService $debsRepository
    ) {
    }
    #[Get("/")]
    public function index()
    {
        return view('Page.PaymentType.index');
    }

    #[Get("/pay-debs")]
    public function paymentDebs()
    {
        $getUserDebs = $this->debsRepository->getUserDebs();
        return view('Page.Payment.PaymentDebs', [
            'data' => $getUserDebs,
            'metode' => PaymentType::all()
        ]);
    }

    // proses pembayaran hutang
    #[RestController(middleware: ['auth:api'])]
    #[Post("/pay-debs")]
    public function payDebs(Request $request)
    {
        try {
            $role = [
                "id" => "required",
                "user_debs_id" => "required",
                "metode" => "required",
                "nominal" => "required",
                "keterangan" => "required",
            ];
            $validated = Validator::make($request->all(), $role);
            if ($validated->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $validated->errors()
                ], 400);
            }
            $load =   $this->debsRepository->payRequest($request->all());
            if ($load) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Berhasil membayar hutang'
                ], 200);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'status' => 'error',
                'message' => $th->getMessage()
            ], 400);
        }
    }
}
