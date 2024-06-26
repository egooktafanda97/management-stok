<?php

namespace App\Services;

use App\Repositories\JenisSatuanRepository;
use App\Repositories\UnitPriecesRepository;

class JenisSatuanService
{
    public function __construct(
        public ActorService $actorService,
        public JenisSatuanRepository $jenisSatuanRepository,
        public KonversiSatuanService $konversiSatuanService,
        public UnitPriecesRepository $unitPriecesRepository
    ) {
    }

    public function create(array $data)
    {
        try {
            return $this->jenisSatuanRepository->transformer([
                'agency_id' => $this->actorService->agency()->id,
                'gudang_id' => $this->actorService->gudang()->id,
                'name' => $data['name'],
            ])
                ->validate()
                ->create();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function findByName(string $name)
    {
        return $this->jenisSatuanRepository->findByName($name, $this->actorService);
    }
    public function getByAgency()
    {
        return $this->jenisSatuanRepository->getByAgency($this->actorService);
    }

    // getByKonversi
    public function getByNotInUnitPriece($produk_id)
    {
        $konversi = $this->konversiSatuanService->getByProduk($produk_id);
        $data = [];
        foreach ($konversi as $key => $value) {
            $read = $this->unitPriecesRepository->getUnitPriecesByProdukId($value->satuan_id, $produk_id);
            if (!$read) {
                $data[] = $this->jenisSatuanRepository->find($value->satuan_id);
            }
        }
        return $data;
    }

    // getByKonversiProduk
    public function getByProduk($produk_id)
    {
        $konversi = $this->konversiSatuanService->getByProduk($produk_id);
        $data = [];
        foreach ($konversi as $key => $value) {
            $data[] = $this->jenisSatuanRepository->find($value->satuan_id);
        }
        return $data;
    }
}
