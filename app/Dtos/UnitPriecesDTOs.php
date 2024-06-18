<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use App\Models\UnitPrieces;

class UnitPriecesDTOs extends BaseDTOs
{
    public function __construct(
        #[Setter] #[Getter]
        public ?int $id = null,

        #[Setter] #[Getter]
        public ?int $user_created_id,

        #[Setter] #[Getter]
        public ?int $agency_id,

        #[Setter] #[Getter]
        public ?int $gudang_id,

        #[Setter] #[Getter]
        public ?int $produks_id,

        #[Setter] #[Getter]
        public ?string $name,

        #[Setter] #[Getter]
        public ?int $priece,

        #[Setter] #[Getter]
        public ?float $priece_decimal,

        // #[Setter] #[Getter]
        // public ?int $jumlah_satan_jual,

        #[Setter] #[Getter]
        public ?int $jenis_satuan_jual_id,

        #[Setter] #[Getter]
        public ?int $diskon,

        #[Setter] #[Getter]
        public ?int $status_id,

        #[Setter] #[Getter]
        public ?int $user_update_id,
    ) {
        parent::__construct();
    }

    public static function fromArray(array $data): UnitPriecesDTOs
    {
        return new self(
            id: $data['id'] ?? null,
            user_created_id: $data['user_created_id'] ?? null,
            agency_id: $data['agency_id'] ?? null,
            gudang_id: $data['gudang_id'] ?? null,
            produks_id: $data['produks_id'] ?? null,
            name: $data['name'] ?? null,
            priece: $data['priece'] ?? null,
            priece_decimal: (float) number_format($data['priece'], 2, '.', '') ?? null,
            // jumlah_satan_jual: $data['jumlah_satan_jual'] ?? null,
            jenis_satuan_jual_id: $data['jenis_satuan_jual_id'] ?? null,
            diskon: $data['diskon'] ?? null,
            status_id: $data['status_id'] ?? null,
            user_update_id: $data['user_update_id'] ?? null,
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
            'name' => $this->name,
            'priece' => $this->priece,
            'priece_decimal' => $this->priece_decimal,
            // 'jumlah_satan_jual' => $this->jumlah_satan_jual,
            'jenis_satuan_jual_id' => $this->jenis_satuan_jual_id,
            'diskon' => $this->diskon,
            'status_id' => $this->status_id,
            'user_update_id' => $this->user_update_id,
        ];
    }

    // setinModels
    public function initModel($id): self
    {
        $model = UnitPrieces::find($id);
        if ($model) {
            $this->id = $this->id  ?? $model->id;
            $this->user_created_id =  $this->user_created_id ?? $model->user_created_id;
            $this->agency_id = $this->agency_id ?? $model->agency_id;
            $this->gudang_id = $this->gudang_id ?? $model->gudang_id;
            $this->produks_id =  $this->produks_id ?? $model->produks_id;
            $this->name = $this->name ?? $model->name;
            $this->priece = $this->priece ?? $model->priece;
            $this->priece_decimal = $this->priece_decimal ?? $model->priece_decimal;
            // $this->jumlah_satan_jual = $this->jumlah_satan_jual  ?? $model->jumlah_satan_jual;
            $this->jenis_satuan_jual_id = $this->jenis_satuan_jual_id ?? $model->jenis_satuan_jual_id;
            $this->diskon =   $this->diskon ?? $model->diskon;
            $this->status_id = $this->status_id ?? $model->status_id;
            $this->user_update_id =  $this->user_update_id ?? $model->user_update_id;
        }
        return $this;
    }

    public static function setUp($id): self
    {
        $model = UnitPrieces::find($id);
        return (
            new self(
                id: $model->id,
                user_created_id: $model->user_created_id,
                agency_id: $model->agency_id,
                gudang_id: $model->gudang_id,
                produks_id: $model->produks_id,
                name: $model->name,
                priece: $model->priece,
                priece_decimal: $model->priece_decimal,
                // jumlah_satan_jual: $model->jumlah_satan_jual,
                jenis_satuan_jual_id: $model->jenis_satuan_jual_id,
                diskon: $model->diskon,
                status_id: $model->status_id,
                user_update_id: $model->user_update_id,
            )
        )->setId($model->id);
    }
}
