<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use App\Models\DebtUsers;

class DebtUsersDTOs extends BaseDTOs
{
    public function __construct(

        #[Setter] #[Getter]
        public ?int $id = null,

        #[Setter] #[Getter]
        public ?int $agency_id = null,

        #[Setter] #[Getter]
        public ?int $gudang_id = null,

        #[Setter] #[Getter]
        public ?int $user_debt_id = null,

        #[Setter] #[Getter]
        public ?int $total = null
    ) {
        parent::__construct();
    }

    public static function fromArray(array $data): DebtUsersDTOs
    {
        return new DebtUsersDTOs(
            id: $data['id'] ?? 0,
            agency_id: $data['agency_id'] ?? null,
            gudang_id: $data['gudang_id'] ?? null,
            user_debt_id: $data['user_debt_id'] ?? null,
            total: $data['total'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'agency_id' => $this->agency_id,
            'gudang_id' => $this->gudang_id,
            'user_debt_id' => $this->user_debt_id,
            'total' => $this->total
        ];
    }

    public static function setUp(int $id): DebtUsersDTOs
    {
        $debtUser = DebtUsers::findOrFail($id);

        return new DebtUsersDTOs(
            id: $debtUser->id,
            agency_id: $debtUser->agency_id,
            gudang_id: $debtUser->gudang_id,
            user_debt_id: $debtUser->user_debt_id,
            total: $debtUser->total
        );
    }
}
