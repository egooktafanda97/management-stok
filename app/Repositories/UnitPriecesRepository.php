<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Dtos\UnitPriecesDTOs;
use App\Models\UnitPrieces;

#[Repository(model: UnitPrieces::class)]
class UnitPriecesRepository extends BaseRepository
{
    public function transformer()
    {
        $data = [
            'user_created_id' => 'user_created_id',
            'agency_id' => 'agency_id',
            'gudang_id' => 'gudang_id',
            'produks_id' => 'produks_id',
            'name' => 'name',
            'priece' => 'priece',
            'priece_decimal' => 'priece_decimal',
            'jumlah_satan_jual' => 'jumlah_satan_jual',
            'jenis_satuan_jual_id' => 'jenis_satuan_jual_id',
            'diskon' => 'diskon',
            'status_id' => 'status_id',
            'user_update_id' => 'user_update_id',
        ];
        $this->setData(array_filter(UnitPriecesDTOs::fromArray($data)->toArray(), fn ($value) => !is_null($value)));
        return $this;
    }

    // remove
    public function removeFromProduksId($prodId)
    {
        try {
            $remove = $this->model->where('produks_id', $prodId)->delete();
            if (!$remove) {
                throw new \Exception("Remove unit prieces failed");
            }
            return  $remove;
        } catch (\Throwable $th) {
            throw new \Exception("Remove unit prieces failed:" . $th->getMessage());
        }
    }

    // findByProdukId
    public function findByProdukId($prodId)
    {
        return $this->model->where('produks_id', $prodId)->get();
    }

    // findByIdANdProdukId
    public function findByIdSatuanAndProdukId($jenisSatuanJualId, $produkId)
    {
        return $this->model->where('jenis_satuan_jual_id', $jenisSatuanJualId)->where('produks_id', $produkId)->first();
    }

    // getUnitPrieces by produk id
    public function getUnitPriecesByProdukId($unit, $produkId)
    {
        return $this->model->where('produks_id', $produkId)
            ->where('jenis_satuan_jual_id', $unit)
            ->first();
    }

    // findByProdukIdPaginate
    public function findByProdukIdPaginate($prodId, $limit = 10)
    {
        return $this->model->where('produks_id', $prodId)
            ->with(["jenisSatuanJual"])
            ->paginate($limit);
    }
}
