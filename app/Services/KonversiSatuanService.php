<?php

namespace App\Services;

use App\Constant\Status;
use App\Dtos\KonversiSatuanDTOs;
use App\Dtos\ProdukDTOs;
use App\Models\JenisSatuan;
use App\Models\Konversisatuan;
use App\Models\Produk;
use App\Repositories\KonversiSatuanRepository;
use App\Repositories\ProdukConfigRepository;
use App\Repositories\ProdukRepository;

class KonversiSatuanService
{
    public KonversiSatuanDTOs $konversiSatuanDTOs;
    public KonversiSatuanDTOs $konversiSatuanDTOsBack;
    public function __construct(
        public KonversiSatuanRepository $konversiSatuanRepository,
        public ActorService $actorService,
        public ProdukRepository $produkRepository,
        public ProdukConfigRepository $produkConfigRepository
    ) {
    }

    public function fromDTOs(KonversiSatuanDTOs $konversiSatuanDTOs): self
    {
        $this->konversiSatuanDTOs = $konversiSatuanDTOs
            ->setAgencyId($this->actorService->agency()->id)
            ->setGudangId($this->actorService->gudang()->id)
            ->setUserCreatedId($this->actorService->authId())
            ->setStatusId(Status::ACTIVE);
        return $this;
    }

    public function create()
    {
        try {
            $store = $this->konversiSatuanRepository
                ->setId($this->konversiSatuanDTOs->getId() ?? null)
                ->setData($this->konversiSatuanDTOs->toArray())
                ->validate()
                ->save();
            if (!$store) {
                throw new \Exception("Create unit prieces failed");
            }
            return KonversiSatuanDTOs::fromArray($store->toArray())->setId($store->id);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }


    // convert
    public  function convertToSatuanStok($produksId,  $from, $qty)
    {
        $produk = (new ProdukDTOs)->init($produksId);
        // konversi
        $nilaiKonvrsiSatuan = $this->konversiSatuanRepository->getKonversiSatuanStok($produksId, $from, $produk->satuanStok->getSatuanStokId());
        if (!$nilaiKonvrsiSatuan)
            throw new \Exception("Konversi satuan tidak ditemukan");
        $nilaiKonversi = $nilaiKonvrsiSatuan->nilai_konversi;
        $konversi = $nilaiKonversi *  $qty;
        return [
            "konversi" => (int) $konversi,
            "from" => JenisSatuan::find($from),
            "to" => JenisSatuan::find($produk->satuanStok->getSatuanStokId()),
        ];
    }

    public  function convert($produksId, $from, $to, $qty)
    {
        $produk = (new ProdukDTOs)->init($produksId);
        // konversi
        $nilaiKonvrsiSatuan = $this->konversiSatuanRepository->getKonversi($produksId, $from, $to);
        if (!$nilaiKonvrsiSatuan)
            throw new \Exception("Konversi satuan tidak ditemukan");
        $nilaiKonversi = $nilaiKonvrsiSatuan->nilai_konversi;
        $konversi = $nilaiKonversi *  $qty;
        return [
            "konversi" => $konversi,
            "from" => JenisSatuan::find($from),
            "to" => JenisSatuan::find($to),
        ];
    }

    public function getByProduk($produkId)
    {
        return $this->konversiSatuanRepository->getByProduk(actorService: $this->actorService, produkId: $produkId);
    }

    public function delete($id)
    {
        try {
            $delete = $this->konversiSatuanRepository
                ->setId($id)
                ->delete();
            if (!$delete) {
                throw new \Exception("Delete unit prieces failed");
            }
            return $delete;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }
}
