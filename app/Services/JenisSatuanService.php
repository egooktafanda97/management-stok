<?php

namespace App\Services;

use App\Repositories\JenisSatuanRepository;

class JenisSatuanService
{
    public function __construct(
        public ActorService $actorService,
        public JenisSatuanRepository $jenisSatuanRepository,
    ) {
    }

    public function create(array $data)
    {
        return $this->jenisSatuanRepository->transformer([
            'agency_id' => $this->actorService->agency()->id,
            'gudang_id' => $this->actorService->gudang()->id,
            'name' => $data['name'],
        ])->validate()->create();
    }

    public function findByName(string $name)
    {
        return $this->jenisSatuanRepository->findByName($name, $this->actorService);
    }
}
