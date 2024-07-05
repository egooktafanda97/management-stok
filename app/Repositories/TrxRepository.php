<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\Transaksi;
use App\Services\ActorService;

#[Repository(Transaksi::class)]
class TrxRepository extends BaseRepository
{
    // findByInvocieId
    public function findByInvoiceId($invoiceId)
    {
        return $this->model->where('invoice_id', $invoiceId)->first();
    }

    public function findByInvoiceNumber($invoiceNumber)
    {
        return $this->model
            ->whereHas('invoice', function ($query) use ($invoiceNumber) {
                $query->where('invoice_id', $invoiceNumber);
            })
            ->with($this->model::allWith())
            ->first();
    }

    // history
    public function history(ActorService $actor, $limit = 10)
    {
        return $this->model
            ->where([
                'agency_id' => $actor->agency()->id,
                'gudang_id' => $actor->gudang()->id
            ])
            ->with($this->model::allWith())
            ->orderBy('created_at', 'desc')
            ->paginate($limit);
    }
}
