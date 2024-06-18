<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use App\Models\Agency;
use App\Models\Gudang;
use App\Models\Kasir;
use App\Models\User;

class ActorDTOs extends BaseDTOs
{
    public function __construct(
        public ?User $userAuth = null,

        #[Setter] #[Getter]
        public ?AgencyDTOs $agency = null,

        #[Setter] #[Getter]
        public ?GudangDTOs $gudang = null,

        #[Setter] #[Getter]
        public ?KasirDTos $kasir = null,

        #[Setter] #[Getter]
        public ?GeneralActorDTOs $generalActor = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'agency' => $this->agency->toArray(),
            'gudang' => $this->gudang->toArray(),
            'kasir' => $this->kasir->toArray(),
        ];
    }

    public static function fromArray(array $data): ActorDTOs
    {
        return new ActorDTOs(
            agency: AgencyDTOs::fromArray($data["agency"])->setId($data["agency"]["id"]),
            gudang: GudangDTOs::fromArray($data["gudang"])->setId($data["gudang"]["id"]),
            kasir: KasirDTos::fromArray($data["kasir"])->setId($data["kasir"]["id"]),
        );
    }
}
