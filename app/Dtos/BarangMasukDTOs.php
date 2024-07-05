<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use App\Models\BarangMasuk;
use Egulias\EmailValidator\Result\Reason\UnclosedComment;

class  BarangMasukDTOs extends BaseDTOs
{
    #[Setter] #[Getter]
    public StokDTOs $stokDTOs;

    public function __construct(
        #[Setter] #[Getter]
        public ?int $id = null,

        #[Setter] #[Getter]
        public ?int $agency_id = 0,

        #[Setter] #[Getter]
        public ?int $gudang_id = 0,

        #[Setter] #[Getter]
        public ?int $user_created_id = 0,

        #[Setter] #[Getter]
        public ?int $produks_id = 0,

        #[Setter] #[Getter]
        public ?int $supplier_id = 0,

        #[Setter] #[Getter]
        public ?int $harga_beli = 0,

        #[Setter] #[Getter]
        public ?int $jumlah_barang_masuk = 0,

        #[Setter] #[Getter]
        public ?int $satuan_beli_id = 0,

        #[Setter] #[Getter]
        public ?int $jumlah_sebelumnya = 0,

        #[Setter] #[Getter]
        public ?int  $satuan_sebelumnya_id = 0,

        #[Setter] #[Getter]
        public ?int $status_id = 0

    ) {
        parent::__construct($id ?? 0);
    }



    public static function fromArray(array $data): self
    {

        return new self(
            id: $data['id'] ?? 0,
            agency_id: $data['agency_id'] ?? 0,
            gudang_id: $data['gudang_id'] ?? 0,
            user_created_id: $data['user_created_id'] ?? 0,
            produks_id: $data['produks_id'] ?? 0,
            supplier_id: $data['supplier_id'] ?? 0,
            harga_beli: $data['harga_beli'] ?? 0,
            jumlah_barang_masuk: $data['jumlah_barang_masuk'] ?? 0,
            satuan_beli_id: $data['satuan_beli_id'] ?? 0,
            jumlah_sebelumnya: $data['jumlah_sebelumnya'] ?? 0,
            satuan_sebelumnya_id: $data['satuan_sebelumnya_id'] ?? 0,
            status_id: $data['status_id'] ?? 0,
        );
    }
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'agency_id' => $this->agency_id,
            'gudang_id' => $this->gudang_id,
            'user_created_id' => $this->user_created_id,
            'produks_id' => $this->produks_id,
            'supplier_id' => $this->supplier_id,
            'harga_beli' => $this->harga_beli,
            'jumlah_barang_masuk' => $this->jumlah_barang_masuk,
            'satuan_beli_id' => $this->satuan_beli_id,
            'jumlah_sebelumnya' => $this->jumlah_sebelumnya,
            'satuan_sebelumnya_id' => $this->satuan_sebelumnya_id,
            'status_id' => $this->status_id,
        ];
    }

    public function init($id)
    {
        $model = BarangMasuk::find($id);
        if (!$model) {
            throw new \Exception("Barang Masuk not found");
        }
        return self::fromArray($model->toArray());
    }
}
