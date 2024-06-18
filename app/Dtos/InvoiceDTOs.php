<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;

class InvoiceDTOs extends BaseDTOs
{
    public function __construct(
        #[Setter] #[Getter]
        public ?int $id = null,

        #[Setter] #[Getter]
        public ?int $agency_id = null,

        #[Setter] #[Getter]
        public ?int $gudang_id = null,

        #[Setter] #[Getter]
        public ?int $users_merchant_id = null,

        #[Setter] #[Getter]
        public ?int $users_trx_id = null,

        #[Setter] #[Getter]
        public ?string $invoice_id = null,

        #[Setter] #[Getter]
        public ?int $trx_types_id = null,

        #[Setter] #[Getter]
        public ?int $payment_type_id  = null,

        #[Setter] #[Getter]
        public ?string $dates = null,

        #[Setter] #[Getter]
        public ?int $status_id = null,
    ) {
    }

    public static function fromArray(array $params): BaseDTOs
    {
        return (
            new self(
                agency_id: $params['agency_id'] ?? null,
                gudang_id: $params['gudang_id'] ?? null,
                users_merchant_id: $params['users_merchant_id'] ?? null,
                users_trx_id: $params['users_trx_id'] ?? null,
                invoice_id: $params['invoice_id'] ?? null,
                trx_types_id: $params['trx_types_id'] ?? null,
                payment_type_id: $params['payment_type_id'] ?? null,
                dates: $params['dates'] ?? null,
                status_id: $params['status_id'] ?? null,
            )
        );
    }

    public function toArray(): array
    {
        return [
            'agency_id' => $this->agency_id,
            'gudang_id' => $this->gudang_id,
            'users_merchant_id' => $this->users_merchant_id,
            'users_trx_id' => $this->users_trx_id,
            'invoice_id' => $this->invoice_id,
            'trx_types_id' => $this->trx_types_id,
            'payment_type_id' => $this->payment_type_id,
            'dates' => $this->dates,
            'status_id' => $this->status_id,
        ];
    }
}
