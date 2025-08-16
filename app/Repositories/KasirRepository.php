<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\Kasir;

#[Repository(model: Kasir::class)]
class KasirRepository extends BaseRepository
{
    public function transformer(array $data): self
    {

        $request = [
            "agency_id" => $data["agency_id"] ?? null, // in set
            "user_id" => $data["user_id"] ?? null, // in set
            "username" => $data->username ?? null, // in set
            "gudang_id" => $data["gudang_id"] ?? null, // in set
            "nama" => $data["nama"] ?? null,
            "alamat" => $data["alamat"] ?? null,
            "telepon" => $data["telepon"] ?? null,
            "deskripsi" =>  $data["deskripsi"] ?? null,
            "saldo" =>  $data["saldo"] ?? 0,
        ];
        $this->data = array_filter($request);
        return $this;
    }
}
