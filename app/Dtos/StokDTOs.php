<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use App\Models\Stok;

class StokDTOs extends BaseDTOs
{
    public function __construct(

        #[Setter] #[Getter]
        public ?int $id = null,

        #[Setter] #[Getter]
        public ?int $agency_id = 0,

        #[Setter] #[Getter]
        public ?int $gudang_id = 0,

        #[Setter] #[Getter]
        public ?int $produks_id = 0,

        #[Setter] #[Getter]
        public ?int $jumlah = 0,

        #[Setter] #[Getter]
        public ?int $satuan_id = 0,

        #[Setter] #[Getter]
        public ?int $jumlah_sebelumnya = 0,

        #[Setter] #[Getter]
        public ?int $satuan_sebelumnya_id = 0,

        #[Setter] #[Getter]
        public ?string $keterangan = "",
    ) {
        parent::__construct($id ?? 0);
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            agency_id: $data['agency_id'] ?? 0,
            gudang_id: $data['gudang_id'] ?? 0,
            produks_id: $data['produks_id'] ?? 0,
            jumlah: $data['jumlah'] ?? 0,
            satuan_id: $data['satuan_id'] ?? 0,
            jumlah_sebelumnya: $data['jumlah_sebelumnya'] ?? 0,
            satuan_sebelumnya_id: $data['satuan_sebelumnya_id'] ?? 0,
            keterangan: $data['keterangan'] ?? "",
        );
    }
    public function toArray(): array
    {
        return [
            'id' => $this->id ?? null,
            'agency_id' => $this->agency_id,
            'gudang_id' => $this->gudang_id,
            'produks_id' => $this->produks_id,
            'jumlah' => $this->jumlah,
            'satuan_id' => $this->satuan_id,
            'jumlah_sebelumnya' => $this->jumlah_sebelumnya,
            'satuan_sebelumnya_id' => $this->satuan_sebelumnya_id,
            'keterangan' => $this->keterangan
        ];
    }

    public function init($id)
    {
        $model = Stok::find($id);
        if ($model) {
            $this->id = $model->id;
            $this->agency_id = $model->agency_id;
            $this->gudang_id = $model->gudang_id;
            $this->produks_id = $model->produks_id;
            $this->jumlah = $model->jumlah;
            $this->satuan_id = $model->satuan_id;
            $this->jumlah_sebelumnya = $model->jumlah_sebelumnya;
            $this->satuan_sebelumnya_id = $model->satuan_sebelumnya_id;
            $this->keterangan = $model->keterangan;
        }
        return $this;
    }
}
