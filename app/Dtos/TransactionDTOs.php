<?php

namespace App\Dtos;

use App\Constant\PayType;
use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use App\Models\Transaksi;

class TransactionDTOs extends BaseDTOs
{
    public UsersDTOs $user;
    public UsersDTOs $userBuyer;

    #[Setter] #[Getter]
    public InvoiceDTOs $invoiceDTOs;


    #[Setter] #[Getter]
    public array $orders = [
        'order_items' => [],
        'pelanggan_id' => null,
        "total_customer_money" => null,
        'payment_type_id' => null,
        "diskon" => null,
        "pph" => null,
    ];

    #[Setter] #[Getter]
    public array $items_order = [];

    /**
     * @var int $totalItemOrder
     * total item order sum qty * price (unit price)
     */
    #[Setter] #[Getter]
    public int $sub_total_order = 0;



    public function __construct(
        #[Setter] #[Getter]
        public ?int $id = null,

        #[Setter] #[Getter]
        public ?int $agency_id = null,

        #[Setter] #[Getter]
        public ?int $gudang_id = null,

        #[Setter] #[Getter]
        public ?int $kasir_id = null,

        #[Setter] #[Getter]
        public ?int $user_kasir_id = null,

        #[Setter] #[Getter]
        public ?int $user_buyer_id = null,

        #[Setter] #[Getter]
        public ?string $invoice_id = null,

        #[Setter] #[Getter]
        public ?string $tanggal = null,

        #[Setter] #[Getter]
        public ?int $diskon = null,

        #[Setter] #[Getter]
        public ?float $total_diskon = null,

        #[Setter] #[Getter]
        public ?int $tax = null,

        #[Setter] #[Getter]
        public ?float $tax_deduction = null,

        #[Setter] #[Getter]
        public ?int $total_gross = null,

        #[Setter] #[Getter]
        public ?int $sub_total = null,

        #[Setter] #[Getter]
        public ?int $payment_type_id = null,

        #[Setter] #[Getter]
        public ?int $status_id = null,

        #[Setter] #[Getter]
        public array $trxDetail = []
    ) {
    }

    public static function fromArray(array $params): BaseDTOs
    {
        return new self(
            id: $params['id'] ?? null,
            agency_id: $params['agency_id'] ?? null,
            gudang_id: $params['gudang_id'] ?? null,
            kasir_id: $params['kasir_id'] ?? null,
            user_kasir_id: $params['user_kasir_id'] ?? null,
            user_buyer_id: $params['user_buyer_id'] ?? null,
            invoice_id: $params['invoice_id'] ?? null,
            tanggal: $params['tanggal'] ?? null,
            diskon: $params['diskon'] ?? null,
            total_diskon: $params['total_diskon'] ?? null,
            tax: $params['tax'] ?? null,
            tax_deduction: $params['tax_deduction'] ?? null,
            total_gross: $params['total_gross'] ?? null,
            sub_total: $params['sub_total'] ?? null,
            payment_type_id: $params['payment_type_id'] ?? null,
            status_id: $params['status_id'] ?? null
        );
    }

    public static function fromModel($model)
    {
        return new self(
            id: $model->id,
            agency_id: $model->agency_id,
            gudang_id: $model->gudang_id,
            kasir_id: $model->kasir_id,
            user_kasir_id: $model->user_kasir_id,
            user_buyer_id: $model->user_buyer_id,
            invoice_id: $model->invoice_id,
            tanggal: $model->tanggal,
            diskon: $model->diskon,
            total_diskon: $model->total_diskon,
            tax: $model->tax,
            tax_deduction: $model->tax_deduction,
            total_gross: $model->total_gross,
            sub_total: $model->sub_total,
            payment_type_id: $model->payment_type_id,
            status_id: $model->status_id
        );
    }

    public function fromDTOs(self $data)
    {
        $this
            ->setAgencyId($data->agency_id)
            ->setGudangId($data->gudang_id)
            ->setKasirId($data->kasir_id)
            ->setUserKasirId($data->user_kasir_id)
            ->setUserBuyerId($data->user_buyer_id)
            ->setInvoiceId($data->invoice_id)
            ->setTanggal($data->tanggal)
            ->setTotalDiskon($data->total_diskon)
            ->setDiskon($data->diskon)
            ->setTax($data->tax)
            ->setTaxDeduction($data->tax_deduction)
            ->setTotalGross($data->total_gross)
            ->setSubTotal($data->sub_total)
            ->setPaymentTypeId($data->payment_type_id)
            ->setStatusId($data->status_id);
        return $this;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'agency_id' => $this->agency_id,
            'gudang_id' => $this->gudang_id,
            'kasir_id' => $this->kasir_id,
            'user_kasir_id' => $this->user_kasir_id,
            'user_buyer_id' => $this->user_buyer_id,
            'invoice_id' => $this->invoice_id,
            'tanggal' => $this->tanggal,
            'diskon' => $this->diskon,
            'total_diskon' => $this->total_diskon,
            'tax' => $this->tax,
            'tax_deduction' => $this->tax_deduction,
            'total_gross' => $this->total_gross,
            'sub_total' => $this->sub_total,
            'payment_type_id' => $this->payment_type_id,
            'status_id' => $this->status_id
        ];
    }

    public function toModel()
    {
        return [
            'agency_id' => $this->agency_id,
            'kasir_id' => $this->kasir_id,
            'user_kasir_id' => $this->user_kasir_id,
            'user_buyer_id' => $this->user_buyer_id,
            'invoice_id' => $this->invoice_id,
            'tanggal' => $this->tanggal,
            'diskon' => $this->diskon,
            'total_diskon' => $this->total_diskon,
            'tax' => $this->tax,
            'tax_deduction' => $this->tax_deduction,
            'total_gross' => $this->total_gross,
            'sub_total' => $this->sub_total,
            'payment_type_id' => $this->payment_type_id,
            'status_id' => $this->status_id
        ];
    }

    public function toModelObject()
    {
        return [
            'agency_id' => $this->agency_id,
            'kasir_id' => $this->kasir_id,
            'user_kasir_id' => $this->user_kasir_id,
            'user_buyer_id' => $this->user_buyer_id,
            'invoice_id' => $this->invoice_id,
            'tanggal' => $this->tanggal,
            'diskon' => $this->diskon,
            'total_diskon' => $this->total_diskon,
            'tax' => $this->tax,
            'tax_deduction' => $this->tax_deduction,
            'total_gross' => $this->total_gross,
            'sub_total' => $this->sub_total,
            'payment_type_id' => $this->payment_type_id,
            'status_id' => $this->status_id
        ];
    }

    public function OrderItems(
        $produks_id = null,
        $qty = null,
        $satuan = null,
    ) {
        $this->items_order[] = [
            'produks_id' => $produks_id,
            'qty' => $qty,
            'satuan' => $satuan,
        ];
        return $this;
    }

    public function Order(
        $pelanggan_id,
        $total_uang_pelanggan,
        $payment_type_id = 1,
        $diskon = 0,
        $pph = false,
    ) {
        $this->orders = [
            'order_items' => $this->items_order,
            'pelanggan_id' => $pelanggan_id,
            "total_customer_money" => $total_uang_pelanggan,
            'payment_type_id' => $payment_type_id ?? PayType::CASH,
            "diskon" => $diskon ?? 0,
            "pph" => $pph ?? false,
        ];
        return $this;
    }

    public function getOrder()
    {
        return $this->orders;
    }

    public function setInvoiceDTOs(InvoiceDTOs $invoiceDTOs)
    {
        $this->invoiceDTOs = $invoiceDTOs;
        return $this;
    }

    public function toTransactionDetail(string $invoiceId)
    {
        $findByInvoce = Transaksi::where('invoice_id', $invoiceId)->first();
        (self::fromModel($findByInvoce))->fromDTOs($this);
    }
}
