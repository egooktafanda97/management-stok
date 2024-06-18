<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\Konversisatuan;

#[Repository(Konversisatuan::class)]
class KonversiSatuanRepository extends BaseRepository
{
    // getKonversi
    public function getKonversiSatuanStok($produksId, $from, $satuanKonversiId)
    {
        return $this->model->where(function ($query) use ($produksId,  $from, $satuanKonversiId) {
            return $query->where('produks_id', $produksId)
                ->where("satuan_id",  $from)
                ->where("satuan_konversi_id", $satuanKonversiId);
        })->first();
    }

    // getKonversi
    public function getKonversi($produksId, $from, $to)
    {
        return $this->model->where(function ($query) use ($produksId, $from, $to) {
            return $query->where('produks_id', $produksId)
                ->where("satuan_id", $from)
                ->where("satuan_konversi_id", $to);
        })->first();
    }

    // getKonversi by produksId and satuanId
    public function getKonversiByProduksIdAndSatuanId($produksId, $satuanId)
    {
        return $this->model->where(function ($query) use ($produksId, $satuanId) {
            return $query->where('produks_id', $produksId)
                ->where("satuan_id", $satuanId);
        })->first();
    }
}
