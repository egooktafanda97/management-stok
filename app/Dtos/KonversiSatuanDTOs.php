<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use App\Models\Konversisatuan;
use App\Models\UnitPrieces;
use PhpParser\Node\Expr\Cast\Double;

class KonversiSatuanDTOs extends BaseDTOs
{
    public function __construct(
        #[Setter] #[Getter]
        public ?int $id = null,

        #[Setter] #[Getter]
        public ?int $user_created_id = null,

        #[Setter] #[Getter]
        public ?int $agency_id = null,

        #[Setter] #[Getter]
        public ?int $gudang_id = null,

        #[Setter] #[Getter]
        public ?int $produks_id = null,

        #[Setter] #[Getter]
        public ?int $satuan_id = null,

        #[Setter] #[Getter]
        public ?int $satuan_konversi_id = null,

        #[Setter] #[Getter]
        public ?float $nilai_konversi = null,

        #[Setter] #[Getter]
        public ?int $status_id = null,
    ) {
        parent::__construct();
    }

    public static function fromArray(array $data): KonversiSatuanDTOs
    {
        return new self(
            id: $data['id'] ?? null,
            user_created_id: $data['user_created_id'] ?? null,
            agency_id: $data['agency_id'] ?? null,
            gudang_id: $data['gudang_id'] ?? null,
            produks_id: $data['produks_id'] ?? null,
            satuan_id: $data['satuan_id'] ?? null,
            satuan_konversi_id: $data['satuan_konversi_id'] ?? null,
            nilai_konversi: $data['nilai_konversi'] ?? null,
            status_id: $data['status_id'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'user_created_id' => $this->user_created_id,
            'agency_id' => $this->agency_id,
            'gudang_id' => $this->gudang_id,
            'produks_id' => $this->produks_id,
            'satuan_id' => $this->satuan_id,
            'satuan_konversi_id' => $this->satuan_konversi_id,
            'nilai_konversi' => $this->nilai_konversi,
            'status_id' => $this->status_id,
        ];
    }

    // setinModels
    public function initModel($id): self
    {
        $model = Konversisatuan::find($id);
        if ($model) {
            $this->id = $this->id ?? $model->id;
            $this->user_created_id = $this->user_created_id ?? $model->user_create_id;
            $this->agency_id = $this->agency_id ?? $model->agency_id;
            $this->gudang_id = $this->gudang_id ?? $model->gudang_id;
            $this->produks_id = $this->produks_id ?? $model->produks_id;
            $this->satuan_id = $this->satuan_id ?? $model->satuan_id;
            $this->satuan_konversi_id = $this->satuan_konversi_id ?? $model->satuan_konversi_id;
            $this->nilai_konversi = $this->nilai_konversi ?? $model->nilai_konversi;
            $this->status_id = $this->status_id ?? $model->status_id;
        }
        return $this;
    }
}
