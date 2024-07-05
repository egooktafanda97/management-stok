<?php

namespace App\Services;

use App\Dtos\StokDTOs;
use App\Repositories\KonversiSatuanRepository;
use App\Repositories\StokRepository;
use Illuminate\Support\Facades\DB;

class StokService
{
    public StokDTOs $stokDTOs;
    public function __construct(
        public StokRepository $stokRepository,
        public KonversiSatuanRepository $konversiSatuanRepository,
    ) {
    }

    public function fromDTOs(StokDTOs $stokDTOs)
    {
        $this->stokDTOs = $stokDTOs
            ->setAgencyId($stokDTOs->agency_id)
            ->setGudangId($stokDTOs->gudang_id);
        return $this;
    }
    // append stok
    public function updateStok()
    {
        DB::beginTransaction();
        try {
            $stok = $this->stokRepository->findStokByProdukId($this->stokDTOs->produks_id);
            if ($stok) {
                $stoks = $this->stokRepository->update($stok->id, [
                    'jumlah' => $this->stokDTOs->jumlah,
                    'satuan_id' => $this->stokDTOs->satuan_id,
                    'jumlah_sebelumnya' =>  $stok->jumlah ?? 0,
                    'satuan_sebelumnya_id' => $stok->satuan_id ?? 0,
                    'keterangan' => $this->stokDTOs->keterangan,
                ]);
            } else {
                $stoks = $this->stokRepository->create($this->stokDTOs->setId(null)->toArray());
            }
            DB::commit();
            return (new StokDTOs())->init($stoks->id);
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function findStokByProdukId($produkId)
    {
        return $this->stokRepository->findStokByProdukId($produkId);
    }
}
