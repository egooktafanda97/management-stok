<?php

namespace App\Dtos;

use App\Contract\AttributesFeature\Attributes\Getter;
use App\Contract\AttributesFeature\Attributes\Setter;
use App\Models\Produk;

class ProdukDTOs extends BaseDTOs
{
    public  $unitPrieces = [];

    public function __construct(
        #[Setter] #[Getter]
        public ?int $id = null,

        #[Setter] #[Getter]
        public ?int $agencyId = null,

        #[Setter] #[Getter]
        public ?int $gudangId = null,

        #[Setter] #[Getter]
        public ?int $userId = null,

        #[Setter] #[Getter]
        public ?string $name = null,

        #[Setter] #[Getter]
        public ?string $deskripsi = null,

        #[Setter] #[Getter]
        public ?string $gambar = null,

        #[Setter] #[Getter]
        public ?int $jenisProdukId = null,

        #[Setter] #[Getter]
        public ?string $barcode = null,

        #[Setter] #[Getter]
        public ?int $rakId = null,

        #[Setter] #[Getter]
        public ?int $statusId = null,

        public ?ProdukConfigDTOs $satuanStok = null,



    ) {
        parent::__construct();
    }

    public function setSatuanStok(ProdukConfigDTOs $prodConf)
    {
        $this->satuanStok = $prodConf;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'agency_id' => $this->agencyId,
            'gudang_id' => $this->gudangId,
            'user_id' => $this->userId,
            'name' => $this->name,
            'deskripsi' => $this->deskripsi,
            'gambar' => $this->gambar,
            'jenis_produk_id' => $this->jenisProdukId,
            'barcode' => $this->barcode,
            'rak_id' => $this->rakId,
            'status_id' => $this->statusId,
            'satuan_stok' => $this->satuanStok->toArray(),
        ];
    }

    public static function fromArray(array $data): self
    {
        $produk = new self(
            id: $data['id'] ?? null,
            agencyId: $data['agency_id'] ?? null,
            gudangId: $data['gudang_id'] ?? null,
            userId: $data['user_id'] ?? null,
            name: $data['name'] ?? null,
            deskripsi: $data['deskripsi'] ?? null,
            gambar: $data['gambar'] ?? null,
            jenisProdukId: $data['jenis_produk_id'] ?? null,
            barcode: $data['barcode'] ?? null,
            rakId: $data['rak_id'] ?? null,
            statusId: $data['status_id'] ?? null,
            satuanStok: ProdukConfigDTOs::fromArray(array_merge($data['satuan_stok'] ?? [], [
                'agency_id' => $data['agency_id'] ?? null,
                'gudang_id' => $data['gudang_id'] ?? null,
            ])),
        );

        return $produk;
    }

    public function trasformer(): array
    {
        $data = self::fromArray($this->toArray())->toArray();
        unset($data['satuan_stok']);
        return array_filter($data);
    }

    public function init($id)
    {
        $model = Produk::find($id);
        if (!$model) {
            throw new \Exception("Produk not found");
        }
        $this->id = $model->id;
        $this->agencyId = $model->agency_id;
        $this->gudangId = $model->gudang_id;
        $this->userId = $model->user_id;
        $this->name = $model->name;
        $this->deskripsi = $model->deskripsi;
        $this->gambar = $model->gambar;
        $this->jenisProdukId = $model->jenis_produk_id;
        $this->barcode = $model->barcode;
        $this->rakId = $model->rak_id;
        $this->statusId = $model->status_id;
        $this->satuanStok = ProdukConfigDTOs::fromArray(array_merge($model->satuanStok->toArray(), [
            'agency_id' => $model->agency_id,
            'gudang_id' => $model->gudang_id,
        ]))->setId($model->satuanStok->id);

        $this->unitPrieces = collect($model->unitPrieces)->map(function ($unit) {
            return UnitPriecesDTOs::fromArray($unit->toArray())->setId($unit->id);
        });
        return $this;
    }
}
