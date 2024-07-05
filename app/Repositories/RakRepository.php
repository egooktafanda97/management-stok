<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\Rak;
use App\Services\ActorService;

#[Repository(model: Rak::class)]
class RakRepository extends BaseRepository
{
    public function __construct(
        public ActorService $actorService,
    ) {
        parent::__construct();
    }
    // getRak
    public function getRak()
    {
        return $this->model->where('agency_id', $this->actorService->agency()->id)
            ->get();
    }
}
