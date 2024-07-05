<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;

class KasirDTos extends BaseDTOs
{
    public function __construct(
        #[Setter] #[Getter]
        public ?int $id = null,

        #[Setter] #[Getter]
        public $agency_id = null,

        #[Setter] #[Getter]
        public $user_id = null,

        #[Setter] #[Getter]
        public $gudang_id = null,

        #[Setter] #[Getter]
        public $nama = null,

        #[Setter] #[Getter]
        public $alamat = null,

        #[Setter] #[Getter]
        public $telepon = null,

        #[Setter] #[Getter]
        public $deskripsi = null,

        #[Setter] #[Getter]
        public $saldo = null,
    ) {
        parent::__construct();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'agency_id' => $this->agency_id,
            'user_id' => $this->user_id,
            'gudang_id' => $this->gudang_id,
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'telepon' => $this->telepon,
            'deskripsi' => $this->deskripsi,
            'saldo' => $this->saldo,
        ];
    }

    public static function fromArray(array $data): KasirDTos
    {
        return (new KasirDTos(
            agency_id: $data["agency_id"] ?? null,
            user_id: $data["user_id"] ?? null,
            gudang_id: $data["gudang_id"] ?? null,
            nama: $data["nama"] ?? null,
            alamat: $data["alamat"] ?? null,
            telepon: $data["telepon"] ?? null,
            deskripsi: $data["deskripsi"] ?? null,
            saldo: $data["saldo"] ?? null,
        ))->setId($data["id"] ?? 0);
    }
    public static function fromModel($model)
    {
        return (new self(
            id: $model->id ?? null,
            agency_id: $model->agency_id,
            user_id: $model->user_id,
            gudang_id: $model->gudang_id,
            nama: $model->nama,
            alamat: $model->alamat,
            telepon: $model->telepon,
            deskripsi: $model->deskripsi,
            saldo: $model->saldo,
        ))->setId($model->id);
    }
}
