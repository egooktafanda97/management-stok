<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;

class TransactionDetailDTOs extends BaseDTOs
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
        public ?int $user_buyer_id = null,

        #[Setter] #[Getter]
        public ?string $invoice_id = null,

        #[Setter] #[Getter]
        public ?int $transaksi_id = null,

        #[Setter] #[Getter]
        public ?int $produks_id = null,

        #[Setter] #[Getter]
        public ?int $unit_priece_id = null,

        #[Setter] #[Getter]
        public ?int $satuan_id = null,

        #[Setter] #[Getter]
        public ?int $priece = null,

        #[Setter] #[Getter]
        public ?float $priece_decimal = null,

        #[Setter] #[Getter]
        public ?int $jumlah = null,

        #[Setter] #[Getter]
        public ?int $total = null,

        #[Setter] #[Getter]
        public ?int $diskon = null,

        #[Setter] #[Getter]
        public ?int $status_id = null
    ) {
    }

    public static function fromArray(array $params): BaseDTOs
    {
        return (
            new self(
                id: $params['id'] ?? null,
                agency_id: $params['agency_id'] ?? null,
                gudang_id: $params['gudang_id'] ?? null,
                user_kasir_id: $params['user_kasir_id'] ?? null,
                user_buyer_id: $params['user_buyer_id'] ?? null,
                invoice_id: $params['invoice_id'] ?? null,
                transaksi_id: $params['transaksi_id'] ?? null,
                produks_id: $params['produks_id'] ?? null,
                unit_priece_id: $params['unit_priece_id'] ?? null,
                satuan_id: $params['satuan_id'] ?? null,
                priece: $params['priece'] ?? null,
                priece_decimal: $params['priece_decimal'] ?? null,
                jumlah: $params['jumlah'] ?? null,
                total: $params['total'] ?? null,
                diskon: $params['diskon'] ?? null,
                status_id: $params['status_id'] ?? null
            )
        )->setId($params['id'] ?? 0);
    }

    public static function fromModel($model)
    {
        return (new self(
            id: $model->id,
            agency_id: $model->agency_id,
            gudang_id: $model->gudang_id,
            user_kasir_id: $model->user_kasir_id,
            user_buyer_id: $model->user_buyer_id,
            invoice_id: $model->invoice_id,
            transaksi_id: $model->transaksi_id,
            produks_id: $model->produks_id,
            unit_priece_id: $model->unit_priece_id,
            satuan_id: $model->satuan_id,
            priece: $model->priece,
            priece_decimal: $model->priece_decimal,
            jumlah: $model->jumlah,
            total: $model->total,
            diskon: $model->diskon,
            status_id: $model->status_id
        ))
            ->setId($model->id);
    }

    public function toModel()
    {
        return [
            'id' => $this->id,
            'agency_id' => $this->agency_id,
            'gudang_id' => $this->gudang_id,
            'user_kasir_id' => $this->user_kasir_id,
            'user_buyer_id' => $this->user_buyer_id,
            'invoice_id' => $this->invoice_id,
            'transaksi_id' => $this->transaksi_id,
            'produks_id' => $this->produks_id,
            'unit_priece_id' => $this->unit_priece_id,
            'satuan_id' => $this->satuan_id,
            'priece' => $this->priece,
            'priece_decimal' => $this->priece_decimal,
            'jumlah' => $this->jumlah,
            'total' => $this->total,
            'diskon' => $this->diskon,
            'status_id' => $this->status_id
        ];
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'agency_id' => $this->agency_id,
            'gudang_id' => $this->gudang_id,
            'user_kasir_id' => $this->user_kasir_id,
            'user_buyer_id' => $this->user_buyer_id,
            'invoice_id' => $this->invoice_id,
            'transaksi_id' => $this->transaksi_id,
            'produks_id' => $this->produks_id,
            'unit_priece_id' => $this->unit_priece_id,
            'satuan_id' => $this->satuan_id,
            'priece' => $this->priece,
            'priece_decimal' => $this->priece_decimal,
            'jumlah' => $this->jumlah,
            'total' => $this->total,
            'diskon' => $this->diskon,
            'status_id' => $this->status_id
        ];
    }
}
