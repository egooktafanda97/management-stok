<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\PaymentType;

#[Repository(model: PaymentType::class)]
class PayTypeTransactionRepository
{
    public function findById(int $id): PaymentType
    {
        return PaymentType::findOrFail($id);
    }
}
