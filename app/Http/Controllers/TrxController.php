<?php

namespace App\Http\Controllers;

use App\Constant\HttpStatus;
use App\Constant\PayType;
use App\Dtos\TransactionDTOs;
use App\Models\ConfigGudang;
use App\Models\ConfigToko;
use App\Models\GeneralActor;
use App\Models\PaymentType;
use App\Repositories\TrxRepository;
use App\Services\ActorService;
use App\Services\HttpResponse;
use App\Services\TrxService;
use App\Services\UnitPriecesService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use TaliumAttributes\Collection\Controller\Controllers;
use TaliumAttributes\Collection\Controller\RestController;
use TaliumAttributes\Collection\Rutes\Get;
use TaliumAttributes\Collection\Rutes\Name;
use TaliumAttributes\Collection\Rutes\Group;
use TaliumAttributes\Collection\Rutes\Post;

#[Controllers()]
#[Group(prefix: 'trx', middleware: ['web',  'auth:web', 'role:kasir,gudang'])]
class TrxController extends Controller
{
    public function __construct(
        public TrxService $trxService,
        public TrxRepository $trxRepository,
        public UnitPriecesService $unitPriecesService,
        public ActorService $actorService,
    ) {}

    #[Get('')]
    public function show()
    {

        return view('Page.Trx.trx');
    }

    #[Get('init-trx')]
    #[RestController(middleware: ['auth:api'])]
    public function initTrx(Request $request)
    {
        try {
            $data = [
                "config" => ConfigGudang::where('gudang_id', $this->trxService->actorService->gudang()->id)->get(),
                "payment_types" => PaymentType::all(),
            ];
            return response()->json($data);
        } catch (\Throwable $th) {
            return response()->json([
                "msg" => 'tidak dapat melalukan init trx'
            ], 500);
        }
    }




    #[Post('shop')]
    #[RestController(middleware: ['auth:api'])]
    public function trxProcessing(Request $request)
    {
        try {
            if (empty($request->user)) {
                $usId = GeneralActor::where([
                    "agency_id" => $this->actorService->agency()->id,
                    "nama" => "umum"
                ])->first()->id ?? null;
                $request->merge([
                    "user" => $usId
                ]);
            }
        } catch (\Throwable $th) {
            return HttpResponse::error('Tidak dapat mengambil user umum.')->code(HttpStatus::HTTP_BAD_REQUEST);
        }
        try {
            $this->trxRepository->setCostumRules([
                'orders' => 'required|array',
                'user' => 'nullable|exists:users,id',
                'payment_type_id' => 'required|exists:payment_types,id',
            ]);

            $this->trxRepository->validate([
                'orders' => $request->orders,
                'user' => $request['user']['id'] ?? $request->user ?? null,
                'payment_type_id' => $request->payType ?? PayType::CASH,
            ]);

            $trxDTos = new TransactionDTOs();

            collect($request->orders)->each(function ($item) use ($trxDTos) {
                $trxDTos->OrderItems(
                    produks_id: $item['id'],
                    qty: $item['qty'],
                    satuan: $this->unitPriecesService->findByIdSatuanAndProdukId(
                        jenisSatuanJualId: $item['satuan'],
                        produkId: $item['id']
                    )
                );
            });
            $transaction = $this->trxService->fromDTOs(
                $trxDTos->order(
                    pelanggan_id: $request['user']['id'] ?? $request->user ?? null,
                    total_uang_pelanggan: $request->total_pembayaran ?? 0,
                    payment_type_id: $request->payType ?? PayType::CASH,
                    diskon: $request->diskon ?? 0,
                    pph: $request->pph ?? false,
                )
            )
                ->middle()
                ->trxProccessing();
            return HttpResponse::success($transaction)->code(HttpStatus::HTTP_OK);
        } catch (\Throwable $th) {
            return HttpResponse::error($th->getMessage())->code(HttpStatus::HTTP_BAD_REQUEST);
            throw $th;
        }
    }

    // #[Get('total-order')]
    // #[RestController(middleware: ['api:auth'])]
    // public function summary(Request $request)
    // {
    //     try {
    //         $required = collect([
    //             'items' => (array) $request->items
    //         ])->toArray();
    //         return response()->json($this->trxService->initialize($required)->summary());
    //     } catch (\Throwable $th) {
    //         return response()->json([
    //             "msg" => 'tidak dapat melalukan summary'
    //         ], 500);
    //     }
    // }

    // faktur
    #[Get('faktur/{invoice}')]
    public function faktur($invoice)
    {
        // return $this->trxService->fackture($invoice);
        return view('Page.Trx.invoice', [
            'invoice' => $invoice,
            "trx" => $this->trxService->fackture($invoice)
        ]);
    }

    // history
    #[Get('history')]
    public function history()
    {
        // return $this->trxService->history();
        return view('Page.Trx.history', [
            'trx' => $this->trxService->history()
        ]);
    }

    // detail history
    #[Get('history/{invoice}')]
    public function detailHistory($invoice)
    {
        $trx =  $this->trxService->fackture($invoice);
        return view('Page.Trx.detail', [
            'trx' => $this->trxService->fackture($invoice),
            'items' => $this->trxService->fackture($invoice)->transaksiDetail,
            "detail" => [
                "Tanggal" => Carbon::parse($trx->tanggal),
                "Instansi" => $trx->agency->nama ?? '-',
                "Merchant" => $trx->gudang->nama ?? '-',
                "Kasir" => $trx->kasir->nama ?? '-',
                "Metode Pembayaran" => $trx->paymentType->name ?? '-',
                "Diskon" => $trx->diskon ?? 0,
                "Total Diskon" => $trx->total_diskon ?? 0,
                "Tax" => $trx->tax ?? 0,
                "Tax Deduction" => $trx->tax_deduction ?? 0,
                "Total Gross" => $trx->total_gross ?? 0,
                "Sub Total" => $trx->sub_total ?? 0,
                "Satus" => $trx->status->nama ?? ''
            ]
        ]);
    }

    // remove
    // #[Get('remove/{invoice}')]
    // public function remove($invoice)
    // {
    //     try {
    //         $trx = $this->trxService->remove($invoice);
    //         return redirect()->route('trx.history');
    //     } catch (\Throwable $th) {
    //         return HttpResponse::error($th->getMessage())->code(HttpStatus::HTTP_BAD_REQUEST);
    //     }
    // }
}
