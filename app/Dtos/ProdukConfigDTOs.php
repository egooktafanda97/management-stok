<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;

class ProdukConfigDTOs extends BaseDTOs
{
    public function __construct(
        #[Setter] #[Getter]
        public ?int $id = null,

        #[Setter] #[Getter]
        public ?int $agencyId = null,

        #[Setter] #[Getter]
        public ?int $gudangId = null,

        #[Setter] #[Getter]
        public ?int $produksId = null,

        #[Setter] #[Getter]
        public ?int $satuanStokId = null,
    ) {
    }

    public function toArray(): array
    {
        return [
            'agency_id' => $this->agencyId,
            'gudang_id' => $this->gudangId,
            'produks_id' => $this->produksId,
            'satuan_stok_id' => $this->satuanStokId,
        ];
    }
    public static function fromArray(array $data): self
    {
        return new self(
            agencyId: $data['agency_id'] ?? null,
            gudangId: $data['gudang_id'] ?? null,
            produksId: $data['produks_id'] ?? null,
            satuanStokId: $data['satuan_stok_id'] ?? null,
        );
    }

    public function transformer(): array
    {
        return collect($this->toArray())
            ->map(function ($value, $key) {
                return [$key => $value];
            })
            ->collapse()
            ->toArray();
    }
}
