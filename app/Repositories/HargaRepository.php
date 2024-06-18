<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\Harga;

#[Repository(model: Harga::class)]
class HargaRepository extends BaseRepository
{
    public function transformer(array $data): self
    {
        $hargaDTO = [
            'user_created_id' => $data['user_created_id'] ?? null,
            'agency_id' => $data['agency_id'] ?? null,
            'gudang_id' => $data['gudang_id'] ?? null,
            'produks_id' => $data['produks_id'] ?? null,
            'jumlah' => $data['jumlah'] ?? null,
            'harga_decimal' => $data['harga_decimal'] ?? null,
            'jenis_satuan_id' => $data['jenis_satuan_id'] ?? null,
            'user_update_id' => $data['user_update_id'] ?? null,
        ];
        if (!empty($this->getId())) {
            foreach ($hargaDTO as $key => $value) {
                if (is_null($value)) {
                    unset($hargaDTO[$key]);
                }
            }
        }
        $this->setData($hargaDTO);
        return $this;
    }
}
