<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Contract\AttributesFeature\Attributes\Set;
use App\Models\Agency;

#[Repository(model: Agency::class)]
class AgencyRepository extends BaseRepository
{
    public function transformer(array $data): self
    {
        $request = [
            'user_id' => $data['user_id'] ?? null,
            'oncard_instansi_id' => $data['oncard_instansi_id'] ?? null,
            'kode_instansi' => $data['kode_instansi'] ?? null,
            'apikeys' => $data['apikeys'] ?? null,
            'nama' => $data['nama'] ?? null,
            'alamat' => $data['alamat'] ?? null,
            'status_id' => $data['status_id'] ?? null
        ];
        $this->data = array_filter($request);
        return $this;
    }

    public function store(array $data = [])
    {
        $created =  $this->create(($data ?? $this->getData()));
        $id = $created->id;

        return $this->findWhereWith(function ($q) use ($id) {
            return $q->whereId($id);
        }, [
            "user",
            "status"
        ]);
    }
}
