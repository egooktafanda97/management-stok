<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\Transaksi;

#[Repository(Transaksi::class)]
class TrxRepository extends BaseRepository
{
    // findByInvocieId
    public function findByInvoiceId($invoiceId)
    {
        return $this->model->where('invoice_id', $invoiceId)->first();
    }
}
