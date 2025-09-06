<?php

namespace Database\Seeders;

use App\Constant\Satuan;
use App\Constant\Status;
use App\Dtos\BarangMasukDTOs;
use App\Dtos\KonversiSatuanDTOs;
use App\Dtos\ProdukDTOs;
use App\Dtos\UnitPriecesDTOs;
use App\Models\Agency;
use App\Models\User;
use App\Services\ActorService;
use App\Services\BarangMasukService;
use App\Services\JenisProdukService;
use App\Services\JenisSatuanService;
use App\Services\KonversiSatuanService;
use App\Services\ProdukService;
use App\Services\UnitPriecesService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function __construct(
        public ProdukService $produkService,
        public JenisSatuanService $jenisSatuanService,
        public JenisProdukService $jenisProdukService,
        public UnitPriecesService $unitPriecesService,
        public KonversiSatuanService $konversiSatuanService,
        public BarangMasukService $barangMasukService,
    ) {}
    /**
     * Run the database seeds.
     */
    public function addProduk1()
    {
        auth()->login(User::whereUsername('toko_demo')->first());
        $prod1 = $this->produkService
            ->fromDTOs(ProdukDTOs::fromArray([
                'name' => 'Produk 1',
                'deskripsi' => 'Deskripsi Produk 1',
                'gambar' => 'gambar1.jpg',
                'jenis_produk_id' => $this->jenisProdukService->findByName('Makanan')->id,
                'barcode' => '124123214',
                'satuan_stok' => [
                    'satuan_stok_id' => Satuan::PCS
                ],
            ]))
            ->addOns(["harga_jual_produk" => 10000])
            ->create();

        // tambahkan unit harga
        $this->unitPriecesService->fromCreatd(UnitPriecesDTOs::fromArray([
            "produks_id" => $prod1->id,
            "name" => "pcs", // nama untit harga mmisal 1 renteng dll.
            "priece" => 1000,
            "jenis_satuan_jual_id" => Satuan::PCS,
            "diskon" => 0, // diskon 3%
        ]))
            ->store();

        $this->unitPriecesService->fromCreatd(UnitPriecesDTOs::fromArray([
            "produks_id" => $prod1->id,
            "name" => "pack", // nama untit harga mmisal 1 renteng dll.
            "priece" => 30000,
            "jenis_satuan_jual_id" => Satuan::PACK,
            "diskon" => 3, // diskon 3%
        ]))
            ->store();

        // tambahkan  item konversi satuan
        # satuan stok pcs to pcs
        $this->konversiSatuanService->fromDTOs(KonversiSatuanDTOs::fromArray([
            'produks_id' => $prod1->id,
            'satuan_id' => Satuan::PCS,
            'satuan_konversi_id' => $prod1->satuanStok->getSatuanStokId(),
            'nilai_konversi' => (float) 1,
        ]))->create();
        # satuan stok pcs to dus
        $this->konversiSatuanService->fromDTOs(KonversiSatuanDTOs::fromArray([
            'produks_id' => $prod1->id,
            'satuan_id' => Satuan::DUS,
            'satuan_konversi_id' => $prod1->satuanStok->getSatuanStokId(),
            'nilai_konversi' => (float) 10,
        ]))->create();

        # satuan stok pcs to dus
        $this->konversiSatuanService->fromDTOs(KonversiSatuanDTOs::fromArray([
            'produks_id' => $prod1->id,
            'satuan_id' => Satuan::PACK,
            'satuan_konversi_id' => $prod1->satuanStok->getSatuanStokId(),
            'nilai_konversi' => (float) 5,
        ]))->create();
        ############################# barang masuk #####################################
        // tambahkan barang masuk
        $this->barangMasukService->fromDTOs((new BarangMasukDTOs(
            produks_id: $prod1->id,
            supplier_id: null,
            harga_beli: 10000,
            jumlah_barang_masuk: 1,
            satuan_beli_id: Satuan::DUS,
        )))->create();
        ################################ Result ########################################
        // produk 1
    }

    // public function addProduk2()
    // {
    //     auth()->login(User::whereUsername('gudang_demo')->first());
    //     $prod2 = $this->produkService
    //         ->fromDTOs(ProdukDTOs::fromArray([
    //             'name' => 'Produk 2',
    //             'deskripsi' => 'Deskripsi Produk 2',
    //             'gambar' => 'gambar2.jpg',
    //             'jenis_produk_id' => $this->jenisProdukService->findByName('Minuman')->id,
    //             'barcode' => '124123214',
    //             'satuan_stok' => [
    //                 'satuan_stok_id' => Satuan::PCS
    //             ],
    //         ]))
    //         ->addOns(["harga_jual_produk" => 20000])
    //         ->create();

    //     // tambahkan unit harga
    //     $this->unitPriecesService->fromCreatd(UnitPriecesDTOs::fromArray([
    //         "produks_id" => $prod2->id,
    //         "name" => "pcs", // nama untit harga mmisal 1 renteng dll.
    //         "priece" => 5000,
    //         "jenis_satuan_jual_id" => Satuan::PCS,
    //         "diskon" => 0, // diskon 3%
    //     ]))
    //         ->store();

    //     $this->unitPriecesService->fromCreatd(UnitPriecesDTOs::fromArray([
    //         "produks_id" => $prod2->id,
    //         "name" => "pack", // nama untit harga mmisal 1 renteng dll.
    //         "priece" => 20000,
    //         "jenis_satuan_jual_id" => Satuan::TOPLES,
    //         "diskon" => 0, // diskon 3%
    //     ]))
    //         ->store();

    //     // tambahkan  item konversi satuan ######################################
    //     # satuan stok pcs to pcs
    //     $this->konversiSatuanService->fromDTOs(KonversiSatuanDTOs::fromArray([
    //         'produks_id' => $prod2->id,
    //         'satuan_id' => Satuan::PCS,
    //         'satuan_konversi_id' => $prod2->satuanStok->getSatuanStokId(),
    //         'nilai_konversi' => (float) 1,
    //     ]))->create();
    //     # satuan stok pcs to toples
    //     $this->konversiSatuanService->fromDTOs(KonversiSatuanDTOs::fromArray([
    //         'produks_id' => $prod2->id,
    //         'satuan_id' => Satuan::TOPLES,
    //         'satuan_konversi_id' => $prod2->satuanStok->getSatuanStokId(),
    //         'nilai_konversi' => (float) 10,
    //     ]))->create();

    //     # satuan stok pcs to dus
    //     $this->konversiSatuanService->fromDTOs(KonversiSatuanDTOs::fromArray([
    //         'produks_id' => $prod2->id,
    //         'satuan_id' => Satuan::DUS,
    //         'satuan_konversi_id' => $prod2->satuanStok->getSatuanStokId(),
    //         'nilai_konversi' => (float) 20,
    //     ]))->create();
    //     ######################################
    //     ############################# barang masuk #####################################
    //     // tambahkan barang masuk
    //     $bMasukService = app()->make(BarangMasukService::class);
    //     $bMasukService->fromDTOs((new BarangMasukDTOs(
    //         produks_id: $prod2->id,
    //         supplier_id: null,
    //         harga_beli: 100000,
    //         jumlah_barang_masuk: 2,
    //         satuan_beli_id: Satuan::DUS,
    //     ))
    //         ->setId(null))
    //         ->create();
    // }


    public function run(): void
    {

        $this->addProduk1();
        // $this->addProduk2();
    }
}

// prod1 result
// {
//     "id": 1,
//     "name": "Produk 1",
//     "jenis_produk_id": 1,
//     "barcode": "124123214",
//     "satuan_stok": {
//         "id": 1,
//         "satuan_stok_id": 1,
//         "satuan_stok": "PCS"
//     },
//     "status_id": 1,
//     "user_id": 1,
//     "agency_id": 1,
//     "gudang_id": 1,
//     "unit_prieces": [
//         {
//             "id": 1,
//             "produks_id": 1,
//             "name": "pcs",
//             "priece": 1000,
//             "jenis_satuan_jual_id": 1,
//             "diskon": 0
//         },
//         {
//             "id": 2,
//             "produks_id": 1,
//             "name": "pack",
//             "priece": 30000,
//             "jenis_satuan_jual_id": 2, // pack
//             "diskon": 3
//         }
//     ],
//     "stok" : {
//         "id": 1,
//         "produks_id": 1,
//         "stok": 10, konvversi dari 1 dus ke pcs = 10
//         "satuan_stok_id": 1
//     }
// }
