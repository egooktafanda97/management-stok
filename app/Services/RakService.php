<?php

namespace App\Services;

use App\Repositories\RakRepository;

class RakService
{
    public function __construct(
        public ActorService $actorService,
        public RakRepository $rakRepository,
    ) {
    }

    // getByUsers
    public function getByUsers()
    {
        return $this->rakRepository->getByUsers($this->actorService);
    }
    public function getRak()
    {
        return $this->rakRepository->getRak();
    }
}
