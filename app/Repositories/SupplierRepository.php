<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\Supplier;
use App\Services\ActorService;

#[Repository(model: Supplier::class)]
class SupplierRepository extends BaseRepository
{
    public function __construct(
        public ActorService $actorService,
    ) {
        parent::__construct();
    }
    // getByUsers
    public function getByUsers()
    {
        return $this->model->where('agency_id', $this->actorService->agency()->id)
            ->get();
    }
}
