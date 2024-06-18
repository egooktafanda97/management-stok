<?php

namespace App\Repositories;

use App\Constant\Status;
use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\Gudang;
use App\Models\JenisSatuan;
use App\Services\ActorService;

#[Repository(model: JenisSatuan::class)]
class JenisSatuanRepository extends BaseRepository
{
    public function transformer(array $data): self
    {
        $this->data =  [
            'agency_id' => $data['agency_id'],
            'gudang_id' => $data['gudang_id'],
            'name' => $data['name'],
        ];
        return $this;
    }

    public function findByName(string $name, ActorService $actor)
    {
        return $this->model->where('name', $name)
            ->where('agency_id', $actor->agency()->id)
            ->where('gudang_id', $actor->gudang()->id)
            ->first();
    }
}
