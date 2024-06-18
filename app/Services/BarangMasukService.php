<?php

namespace App\Services;

use App\Constant\Status;
use App\Dtos\BarangMasukDTOs;
use App\Dtos\ProdukDTOs;
use App\Dtos\StokDTOs;
use App\Repositories\BarangMasukRepository;
use App\Repositories\KonversiSatuanRepository;
use App\Repositories\ProdukRepository;
use App\Repositories\StokRepository;

class BarangMasukService
{
    public BarangMasukDTOs $barangMasukDTOs;
    public ProdukDTOs $produkDTOs;
    public  $stokDTOs;

    public function __construct(
        protected BarangMasukRepository $barangMasukRepository,
        protected StokRepository $stokRepository,
        protected KonversiSatuanService $konversiSatuanService,
        protected ActorService $actorService,
        public StokService $stokService,
        public ProdukRepository $produkRepository
    ) {
    }

    public function fromDTOs(BarangMasukDTOs $barangMasukDTOs)
    {
        try {
            $this->produkDTOs = (new  ProdukDTOs())->init($barangMasukDTOs->produks_id);
            $stok = $this->stokRepository->findStokByProdukId($barangMasukDTOs->produks_id);
            $findBarangMasuk = $this->barangMasukRepository->find($barangMasukDTOs->id);

            $this->barangMasukDTOs = $barangMasukDTOs
                ->setId($barangMasukDTOs->id ?? 0)
                ->setAgencyId($this->actorService->agency()->id)
                ->setGudangId($this->actorService->gudang()->id)
                ->setUserCreatedId($this->actorService->authId())
                ->setJumlahSebelumnya($findBarangMasuk ? $findBarangMasuk->jumlah_sebelumnya : $stok->jumlah ?? 0)
                ->setSatuanSebelumnyaId($stok->satuan_id ??  $this->produkDTOs->satuanStok->getSatuanStokId())
                ->setStatusId(Status::Completed);


            $konversiSatuan = $this->konversiSatuanService->convertToSatuanStok($this->produkDTOs->id, $this->barangMasukDTOs->satuan_beli_id, $this->barangMasukDTOs->jumlah_barang_masuk);
            $satuanSebelumnya = $stok->satuan_id ??  $this->produkDTOs->satuanStok->getSatuanStokId();
            if ($findBarangMasuk) {
                $konversiJumlahSebelumnya = $this->konversiSatuanService->convertToSatuanStok($this->produkDTOs->id, $findBarangMasuk->satuan_beli_id, $findBarangMasuk->jumlah_barang_masuk)['konversi'] ?? 0;
                $jumlahSebelumnya =  ((int)($stok->jumlah ?? 0) - $konversiJumlahSebelumnya);
                $newAmount = ((int)($stok->jumlah ?? 0) - $konversiJumlahSebelumnya) + (int)$konversiSatuan['konversi'];
                $newSatuan = $konversiSatuan['satuan_id'] ??  $this->produkDTOs->satuanStok->getSatuanStokId();
            } else {
                $jumlahSebelumnya =  $stok->jumlah ?? 0;
                $newAmount =  (($stok->jumlah ?? 0) + (int)$konversiSatuan['konversi']);
                $newSatuan = $konversiSatuan['satuan_id'] ??  $this->produkDTOs->satuanStok->getSatuanStokId();
            }


            $this->stokDTOs = new StokDTOs(
                agency_id: $this->actorService->agency()->id,
                gudang_id: $this->actorService->gudang()->id,
                produks_id: $this->barangMasukDTOs->produks_id,
                jumlah: $newAmount,
                satuan_id: $newSatuan,
                jumlah_sebelumnya: $jumlahSebelumnya ?? 0,
                satuan_sebelumnya_id: $satuanSebelumnya,
                keterangan: "Barang Masuk"
            );
            return $this;
        } catch (\Throwable $th) {
            throw new \Exception('gagal mengtur data barang masuk: ' . $th->getMessage());
        }
    }

    public function getData()
    {
        return $this->barangMasukDTOs
            ->setStokDTOs($this->stokDTOs);
    }

    public function create()
    {
        try {

            $store = $this->barangMasukRepository
                ->setId($this->barangMasukDTOs->id ?? null)
                ->setData($this->barangMasukDTOs->toArray())
                ->validate()
                ->save();


            try {
                $this->stokService->fromDTOs($this->stokDTOs)
                    ->updateStok();
            } catch (\Throwable $th) {
                throw new \Exception('update stok error:' . $th->getMessage());
            }
            if (!$store) {
                throw new \Exception("Create barang masuk failed");
            }
            return BarangMasukDTOs::fromArray($store->toArray())->setId($store->id);
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    // cencel barang masuk
    public function cancel($id)
    {
        try {
            $findBarangMasuk = $this->barangMasukRepository->find($id);
            $this->produkDTOs = (new  ProdukDTOs())->init($findBarangMasuk->produks_id);
            $stok = $this->stokRepository->findStokByProdukId($findBarangMasuk->produks_id);
            $konversiJumlahSebelumnya = $this->konversiSatuanService->convertToSatuanStok($this->produkDTOs->id, $findBarangMasuk->satuan_beli_id, $findBarangMasuk->jumlah_barang_masuk)['konversi'] ?? 0;
            $jumlahSebelumnya =  ((int)($stok->jumlah ?? 0) - $konversiJumlahSebelumnya);
            $this->stokDTOs = new StokDTOs(
                agency_id: $this->actorService->agency()->id,
                gudang_id: $this->actorService->gudang()->id,
                produks_id: $findBarangMasuk->produks_id,
                jumlah: $jumlahSebelumnya,
                satuan_id: $stok->satuan_id ??  $this->produkDTOs->satuanStok->getSatuanStokId(),
                jumlah_sebelumnya: $jumlahSebelumnya ?? 0,
                satuan_sebelumnya_id: $stok->satuan_id ??  $this->produkDTOs->satuanStok->getSatuanStokId(),
                keterangan: "Barang Masuk"
            );
            $this->stokService->fromDTOs($this->stokDTOs)->updateStok();
            $this->barangMasukRepository->delete($id);
            return true;
        } catch (\Throwable $th) {
            throw new \Exception($th->getMessage());
        }
    }

    public function getBarangMasukSebelumnya($produkId)
    {
    }
}
