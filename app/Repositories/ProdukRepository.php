<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Dtos\ProdukDTOs;
use App\Models\Produk;

#[Repository(model: Produk::class)]
class ProdukRepository extends BaseRepository
{

    public UnitPriecesRepository $unitPriecesRepository;

    public function Inject(array $classInject)
    {
        $this->unitPriecesRepository = $classInject['unitPriecesRepository'];
    }

    public function transformer(array $data): self
    {
        $this->setData($data);
        return $this;
    }

    public function getInitPrice($produkId)
    {
        $produk = $this->model->find($produkId);
        $satuanStok = $produk->satuanStok;
        $unitPrieces = $this->unitPriecesRepository->model
            ->whereProduksId($produkId)
            ->where('jenis_satuan_jual_id', $satuanStok->satuan_stok_id)
            ->with(['jenisSatuanJual'])
            ->first();
        return $unitPrieces ?? 0;
    }

    // search produk
    public function searchProduk($search, $limit)
    {
        $this->setRelationWith($this->model::allWith());
        $items = $this->search(callback: function ($qury) use ($search) {
            return $qury->where('name', 'like', "%$search%")
                ->orWhere('barcode', $search);
        }, limit: $limit);
        return $items->map(function ($item) {
            $initialPriece = $this->getInitPrice($item->id);
            $item->init_unit = $initialPriece;
            $item->priceTotal = $initialPriece->priece - ($initialPriece->priece * $initialPriece->diskon / 100);
            $item->total_diskon = ($initialPriece->priece * $initialPriece->diskon / 100);
            $item->satuan_aktif = $initialPriece->jenis_satuan_jual_id;
            return $item;
        });
    }
}
