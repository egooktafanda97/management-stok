<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Dtos\ProdukDTOs;
use App\Models\Produk;

#[Repository(model: Produk::class)]
class ProdukRepository extends BaseRepository
{

    public function transformer(array $data): self
    {
        $this->setData($data);
        return $this;
    }
}
