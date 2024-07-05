<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Dtos\ProdukConfigDTOs;
use App\Models\ProduksConfig;

#[Repository(model: ProduksConfig::class)]
class ProdukConfigRepository extends BaseRepository
{
    public function transformer(array $data): self
    {
        $this->setData(ProdukConfigDTOs::fromArray($data)->toArray());
        return $this;
    }

    public function removeFromProduksId($prodId)
    {

        try {
            $remove = $this->model::where('produks_id', $prodId)->delete();
            if (!$remove) {
                throw new \Exception("Remove produk config failed");
            }
            return true;
        } catch (\Throwable $th) {
            throw new \Exception("Remove produk config failed:" . $th->getMessage());
        }
    }
}
