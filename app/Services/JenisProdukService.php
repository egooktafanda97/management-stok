<?php

namespace App\Services;

use App\Repositories\JenisProdukRepository;
use App\Repositories\JenisSatuanRepository;

class JenisProdukService
{
    public function __construct(
        public ActorService $actorService,
        public JenisProdukRepository $jenisProdukRepository,
    ) {
    }

    // crud
    public function create(array $data)
    {
        return $this->jenisProdukRepository->transformer([
            'agency_id' => $this->actorService->agency()->id,
            'gudang_id' => $this->actorService->gudang()->id,
            'name' => $data['name'],
        ])->validate()->create();
    }

    public function update(int $id, array $data)
    {
        return $this->jenisProdukRepository->update($id, $data);
    }


    public function delete(int $id)
    {
        return $this->jenisProdukRepository->delete($id);
    }

    public function findByName(string $name)
    {
        return $this->jenisProdukRepository->findByName($name, $this->actorService);
    }
}
