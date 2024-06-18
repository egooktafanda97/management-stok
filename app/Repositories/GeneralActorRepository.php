<?php

namespace App\Repositories;

use App\Constant\Status;
use App\Contract\AttributesFeature\Attributes\Repository;
use App\Models\GeneralActor;

#[Repository(model: GeneralActor::class)]
class GeneralActorRepository extends BaseRepository
{
    public function transformer(array $data): self
    {
        $request = [
            "agency_id" => $data["agency_id"] ?? null,
            'oncard_instansi_id' => $data['oncard_instansi_id'] ?? null,
            'oncard_instansi_id' => $data['oncard_instansi_id'] ?? null,
            "user_id" => $data["user_id"] ?? null,
            "oncard_user_id" => $data["oncard_user_id"] ?? null,
            "oncard_account_number" => $data["oncard_account_number"] ?? null,
            "nama" => $data["nama"] ?? null,
            "user_type" => $data["user_type"] ?? null,
            "sync_date" => $data["sync_date"] ?? null,
            "detail" => $data["detail"] ?? null,
        ];
        $this->data = array_filter($request, fn ($value) => !is_null($value));
        return $this;
    }
}
