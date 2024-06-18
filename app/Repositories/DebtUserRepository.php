<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\DebtUsers;

#[Repository(model: DebtUsers::class)]
class DebtUserRepository extends BaseRepository
{

    // findByUserDebtId
    public function findByUserDebtId($userDebs)
    {
        return $this->model->where('user_debt_id', $userDebs)->first();
    }
}
