<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;

class GudangDTOs extends BaseDTOs
{
    public function __construct(
        #[Setter] #[Getter]
        public ?int $id = null,

        #[Setter] #[Getter]
        public $agency_id = null,

        #[Setter] #[Getter]
        public $user_id = null,

        #[Setter] #[Getter]
        public $nama = null,

        #[Setter] #[Getter]
        public $alamat = null,

        #[Setter] #[Getter]
        public $telepon = null,

        #[Setter] #[Getter]
        public $logo = null,

        #[Setter] #[Getter]
        public $deskripsi = null,

        #[Setter] #[Getter]
        public $status_id = null,
    ) {
        parent::__construct();
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'agency_id' => $this->agency_id,
            'user_id' => $this->user_id,
            'nama' => $this->nama,
            'alamat' => $this->alamat,
            'telepon' => $this->telepon,
            'logo' => $this->logo,
            'deskripsi' => $this->deskripsi,
            'status_id' => $this->status_id,
        ];
    }

    public static function fromArray(array $data): GudangDTOs
    {
        return new GudangDTOs(
            id: $data["id"] ?? 0,
            agency_id: $data["agency_id"] ?? null,
            user_id: $data["user_id"] ?? null,
            nama: $data["nama"] ?? null,
            alamat: $data["alamat"] ?? null,
            telepon: $data["telepon"] ?? null,
            logo: $data["logo"] ?? null,
            deskripsi: $data["deskripsi"] ?? null,
            status_id: $data["status_id"] ?? null,
        );
    }

    public static function fromModel($model)
    {
        return new GudangDTOs(
            id: $model->id,
            agency_id: $model->agency_id,
            user_id: $model->user_id,
            nama: $model->nama,
            alamat: $model->alamat,
            telepon: $model->telepon,
            logo: $model->logo,
            deskripsi: $model->deskripsi,
            status_id: $model->status_id,
        );
    }
}
