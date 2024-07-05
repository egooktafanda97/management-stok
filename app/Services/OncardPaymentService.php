<?php

namespace App\Services;

use App\Dtos\TransactionDTOs;
use App\Models\Invoices;
use App\Models\Keyvendorpayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OncardPaymentService
{
    public TransactionDTOs $trxDtos;
    public Request $request;
    public $buyerActor;

    public function __construct(
        public ActorService $actorService,
        Request $request
    ) {
        $this->request = $request;
    }


    public function setUp($trxDto, $buyerActor)
    {
        $this->trxDtos = $trxDto;
        $this->buyerActor = $buyerActor;
    }



    public function payment()
    {
        try {
            $formData = [
                "total" => $this->trxDtos->getSubTotal(),
                "signature" => $this->request->token ?? "test",
                "items" => json_encode(collect($this->trxDtos->toTransactionModelDetai($this->trxDtos->getInvoiceId()))->toArray()),
                "reference" => Invoices::whereId($this->trxDtos->getInvoiceId())->first()->invoice_id,
                "card" => $this->buyerActor->card_hash,
                "pin" => $this->request->pin,
                "app_name" => "warehouse-oncard",
            ];
            $apiKey = Keyvendorpayment::where("app", 'oncard')->where("gudang_id", $this->actorService->gudang()->id)->first();
            $oncard = env("ONCARDURLAPP");
            $trxOncard = Http::withHeaders([
                "api-key" => $apiKey->apikeys
            ])
                ->post($oncard . "api/v1/oncard/payment", $formData);
            if ($trxOncard->status() == 200) {
                return $trxOncard->json();
            } else {
                throw new \Exception($trxOncard->json()['message'], 500);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
