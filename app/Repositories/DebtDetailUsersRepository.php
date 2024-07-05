<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\DebtDetailUsers;

#[Repository(model: DebtDetailUsers::class)]
class DebtDetailUsersRepository extends BaseRepository
{
}
