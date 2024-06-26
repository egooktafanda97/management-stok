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

    // /getPelanggan
    public function getPelanggan($actorService)
    {
        return $this->model
            ->where('agency_id', $actorService->agency()->id)
            ->with($this->model::withAll())
            ->orderBy('id', 'desc')
            ->paginate(20);
    }

    // getPelangganById
    public function getPelangganById($id, $actorService)
    {
        return $this->model
            ->where('agency_id', $actorService->agency()->id)
            ->with($this->model::withAll())
            ->first();
    }

    // searchGeneralActor
    public function searchGeneralActor($search, $actorService)
    {
        return $this->model
            ->where('agency_id', $actorService->agency()->id)
            ->where(function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('oncard_account_number', 'like', '%' . $search . '%');
            })
            ->with($this->model::withAll())
            ->orderBy('id', 'desc')
            ->paginate(10);
    }

    // searchGeneralActorNopaginate
    public function searchGeneralActorNopaginate($search, $actorService)
    {
        return $this->model
            ->where('agency_id', $actorService->agency()->id)
            ->where(function ($query) use ($search) {
                $query->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('oncard_account_number', 'like', '%' . $search . '%');
            })
            ->with($this->model::withAll())
            ->take(10)
            ->orderBy('id', 'desc')
            ->get();
    }
}
