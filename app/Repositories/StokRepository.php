<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\Stok;

#[Repository(Stok::class)]
class StokRepository extends BaseRepository
{
    public function findStokByProdukId($produkId)
    {
        return $this->model->where('produks_id', $produkId)->first();
    }
}
