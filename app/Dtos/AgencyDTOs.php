<?php

namespace App\Dtos;

use App\Constant\Status;
use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use App\Models\Agency;

class AgencyDTOs extends BaseDTOs
{

    public function __construct(
        #[Setter] #[Getter]
        public ?int    $id = null,

        #[Setter] #[Getter]
        public ?int    $oncard_instansi_id = null,

        #[Setter] #[Getter]
        public ?string $kode_instansi = null,

        #[Setter] #[Getter]
        public ?string $nama = null,

        #[Setter] #[Getter]
        public ?string $username = null,

        #[Setter] #[Getter]
        public ?int $user_id = null,

        #[Setter] #[Getter]
        public ?string $alamat = null,

        #[Setter] #[Getter]
        public ?int $status_id = null,
    ) {
        // parent::__construct();
    }


    public function test()
    {
        return true;
    }

    public function getTransformerInAgencyModel(Agency $data): array
    {
        return [
            'id' => $data->id ?? null,
            'oncard_instansi_id' => $data->oncard_instansi_id,
            'user_id' => $data->user_id,
            'status_id' => $data->status->id,
            'kode_instansi' => $data->kode_instansi,
            'nama' => $data->nama,
            'username' => $data->user->username,
            'alamat' => $data->alamat,
            'status_id' => $data->status->nama,
        ];
    }

    public function toArray(): array
    {
        return [
            'oncard_instansi_id' => $this->oncard_instansi_id,
            'kode_instansi' => $this->kode_instansi,
            'nama' => $this->nama,
            'username' => $this->username,
            'user_id' => $this->user_id,
            'alamat' => $this->alamat,
            'status_id' => $this->status_id,
        ];
    }

    public static function fromArray(array $data): self
    {
        return new self(
            $data['oncard_instansi_id'] ?? null,
            $data['kode_instansi'] ?? null,
            $data['nama'] ?? null,
            $data['username'] ?? null,
            $data['user_id'] ?? null,
            $data['alamat'] ?? null,
            $data['status_id'] ?? null,
        );
    }

    public function getOnCardInstansiId(): ?int
    {
        return $this->oncard_instansi_id;
    }

    public function getKodeInstansi(): ?string
    {
        return $this->kode_instansi;
    }

    public static function fromModel($model)
    {
        return new self(
            oncard_instansi_id: $model->oncard_instansi_id,
            kode_instansi: $model->kode_instansi,
            nama: $model->nama,
            username: $model->user->username,
            user_id: $model->user_id,
            alamat: $model->alamat,
            status_id: $model->status_id,
        );
    }
}
