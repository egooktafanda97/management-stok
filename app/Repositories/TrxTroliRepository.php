<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\TrxDetail;

#[Repository(TrxDetail::class)]
class TrxTroliRepository extends BaseRepository
{
    // updateStatusByInvoice
    public function updateStatusByInvoice($invoiceId, $statusId)
    {
        return $this->model
            ->where('invoice_id', $invoiceId)
            ->update(['status_id' => $statusId]);
    }
}
