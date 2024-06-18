<?php

namespace App\Repositories;

use App\Constant\Status;
use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\Gudang;

#[Repository(model: Gudang::class)]
class GudangRepository extends BaseRepository
{
    public function transformer(array $data): self
    {
        $request =  [
            "agency_id" => $data["agency_id"] ?? null,
            "user_id" =>  $data["user_id"] ?? null,
            "nama" =>  $data["nama"] ?? null,
            "alamat" => $data["alamat"] ?? null,
            "telepon" =>  $data["telepon"] ?? null,
            "logo" => $data["logo"] ?? null,
            "deskripsi" =>  $data["deskripsi"] ?? null,
            "status_id" => $data["status_id"] ?? Status::ACTIVE
        ];
        $this->data = array_filter($request);
        return $this;
    }
}
