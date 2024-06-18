<?php

namespace App\Dtos;


use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use App\Models\DebtDetailUsers;

class DebtDetailUserDTOs extends BaseDTOs
{
    public function __construct(

        #[Setter] #[Getter]
        public ?int $id = null,

        #[Setter] #[Getter]
        public ?int $agency_id = null,

        #[Setter] #[Getter]
        public ?int $gudang_id = null,

        #[Setter] #[Getter]
        public ?int $user_kasir_id = null,

        #[Setter] #[Getter]
        public ?int $user_debt_id = null,

        #[Setter] #[Getter]
        public ?int $invoice_id = null,

        #[Setter] #[Getter]
        public ?int $total_hutang = null,

        #[Setter] #[Getter]
        public ?int $total_bayar = null,

        #[Setter] #[Getter]
        public ?int $total_sisa = null,

        #[Setter] #[Getter]
        public ?int $payment_type_id = null,

        #[Setter] #[Getter]
        public ?int $status_id = null
    ) {
        parent::__construct();
    }

    public static function fromArray(array $data): DebtDetailUserDTOs
    {
        return new DebtDetailUserDTOs(
            id: $data['id'] ?? 0,
            agency_id: $data['agency_id'] ?? null,
            gudang_id: $data['gudang_id'] ?? null,
            user_kasir_id: $data['user_kasir_id'] ?? null,
            user_debt_id: $data['user_debt_id'] ?? null,
            invoice_id: $data['invoice_id'] ?? null,
            total_hutang: $data['total_hutang'] ?? null,
            total_bayar: $data['total_bayar'] ?? null,
            total_sisa: $data['total_sisa'] ?? null,
            payment_type_id: $data['payment_type_id'] ?? null,
            status_id: $data['status_id'] ?? null
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'agency_id' => $this->agency_id,
            'gudang_id' => $this->gudang_id,
            'user_kasir_id' => $this->user_kasir_id,
            'user_debt_id' => $this->user_debt_id,
            'invoice_id' => $this->invoice_id,
            'total_hutang' => $this->total_hutang,
            'total_bayar' => $this->total_bayar,
            'total_sisa' => $this->total_sisa,
            'payment_type_id' => $this->payment_type_id,
            'status_id' => $this->status_id
        ];
    }

    public static function setUp(int $id): DebtDetailUserDTOs
    {
        $debtDetailUser = DebtDetailUsers::findOrFail($id);

        return new DebtDetailUserDTOs(
            id: $debtDetailUser->id,
            agency_id: $debtDetailUser->agency_id,
            gudang_id: $debtDetailUser->gudang_id,
            user_kasir_id: $debtDetailUser->user_kasir_id,
            user_debt_id: $debtDetailUser->user_debt_id,
            invoice_id: $debtDetailUser->invoice_id,
            total_hutang: $debtDetailUser->total_hutang,
            total_bayar: $debtDetailUser->total_bayar,
            total_sisa: $debtDetailUser->total_sisa,
            payment_type_id: $debtDetailUser->payment_type_id,
            status_id: $debtDetailUser->status_id
        );
    }
}
