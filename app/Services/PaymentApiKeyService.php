<?php

namespace App\Services;

use App\Constant\Status;
use App\Models\Keyvendorpayment;

class PaymentApiKeyService
{

    public function __construct(
        public ActorService $actorService
    ) {
    }

    public function generate($apiKeys, $gudang)
    {
        try {
            Keyvendorpayment::create([
                'agency_id' => $this->actorService->agency()->id,
                'gudang_id' => $gudang->id,
                'user_gudang_id' => $gudang->user_id,
                'apikeys' => $apiKeys,
                'status_id' => Status::ACTIVE
            ]);
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function nonActive($id)
    {
        try {
            $api = Keyvendorpayment::find($id);
            $api->status_id = Status::INACTIVE;
            $api->save();
            return true;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findApi($api)
    {
        $get = Keyvendorpayment::whereApikeys($api)->where("status", Status::ACTIVE)->fist();
        return $get;
    }
}
