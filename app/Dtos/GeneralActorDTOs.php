<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use Illuminate\Support\Facades\Date;

class GeneralActorDTOs extends BaseDTOs
{
    public function __construct(
        #[Setter] #[Getter]
        public ?int $id = null,

        #[Setter] #[Getter]
        public ?int $agency_id = null,

        #[Setter] #[Getter]
        public ?string $oncard_instansi_id = null,

        #[Setter] #[Getter]
        public ?int $user_id = null,

        #[Setter] #[Getter]
        public ?int $oncard_user_id = null,

        #[Setter] #[Getter]
        public ?string $oncard_account_number = null,

        #[Setter] #[Getter]
        public ?string $nama = null,

        #[Setter] #[Getter]
        public ?string $user_type = null,

        #[Setter] #[Getter]
        public ?string $sync_date = null,

        #[Setter] #[Getter]
        public ?string $detail = null,

        #[Setter] #[Getter]
        public ?UsersDTOs $user = null,

    ) {
        parent::__construct();
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data["id"] ?? 0,
            agency_id: $data["agency_id"] ?? null,
            oncard_instansi_id: $data["oncard_instansi_id"] ?? null,
            user_id: $data["user_id"] ?? null,
            oncard_user_id: $data["oncard_user_id"] ?? null,
            oncard_account_number: $data["oncard_account_number"] ?? null,
            nama: $data["nama"] ?? null,
            user_type: $data["user_type"] ?? null,
            sync_date: $data["sync_date"] ?? null,
            detail: $data["detail"] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            "id" => $this->id ?? null,
            "agency_id" => $this->agency_id,
            "user_id" => $this->user_id,
            'oncard_instansi_id' => $this->oncard_instansi_id,
            "oncard_user_id" => $this->oncard_user_id,
            "oncard_account_number" => $this->oncard_account_number,
            "nama" => $this->nama,
            "user_type" => $this->user_type,
            "sync_date" => $this->sync_date,
            "detail" => $this->detail,
            "user" => $this->user?->toArray() ?? null
        ];
    }

    public static function fromModel($model)
    {
        return (
            new self(
                id: $model->id ?? null,
                agency_id: $model->agency_id ?? null,
                oncard_instansi_id: $model->oncard_instansi_id ?? null,
                user_id: $model->user_id ?? null,
                oncard_user_id: $model->oncard_user_id ?? null,
                oncard_account_number: $model->oncard_account_number ?? null,
                nama: $model->nama ?? null,
                user_type: $model->user_type ?? null,
                sync_date: $model->sync_date ?? null,
                detail: $model->detail ?? null
            )
        )
            ->setId($model->id);
    }
}
