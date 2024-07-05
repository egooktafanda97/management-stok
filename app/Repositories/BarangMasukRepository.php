<?php

namespace App\Repositories;

use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\BarangMasuk;
use App\Services\ActorService;
use App\Services\KonversiSatuanService;

#[Repository(BarangMasuk::class)]
class BarangMasukRepository extends BaseRepository
{
    public function __construct(
        public ActorService $actorService
    ) {
        parent::__construct();
    }

    public function getBarangMasuk()
    {
        return $this->model
            ->where('agency_id', $this->actorService->agency()->id)
            ->where('gudang_id', $this->actorService->gudang()->id)
            ->with($this->model::withAll())
            ->orderBy('id', 'desc')
            ->paginate(20);
    }

    public function getBarangMasukToday()
    {
        $items =  $this->model
            ->where('agency_id', $this->actorService->agency()->id)
            ->where('gudang_id', $this->actorService->gudang()->id)
            ->whereDate('created_at', now())
            ->with($this->model::withAll())
            ->orderBy('id', 'desc')
            ->paginate(20);
        $items->getCollection()->transform(function ($items) {
            $items->stok = $items->jumlah_barang_masuk +
                (app(KonversiSatuanService::class)->convertToSatuanStok($items->produks_id, $items->satuan_beli_id, $items->jumlah_barang_masuk)['konversi'] ?? 0);
            $items->produkPalingBaru =
                $this->model->where('produks_id', $items->produks_id)
                ->orderBy('id', 'desc')
                ->first()->id == $items->id;
            $items->nilai_konversi = app(KonversiSatuanService::class)->convertToSatuanStok($items->produks_id, $items->satuan_beli_id, $items->jumlah_barang_masuk)['konversi'] ?? 0;
            return $items;
        });
        return $items;
    }
}
