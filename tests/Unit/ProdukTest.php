<?php

namespace Tests\Unit;

use App\Constant\Satuan;
use App\Constant\Status;
use App\Dtos\BarangMasukDTOs;
use App\Dtos\KonversiSatuanDTOs;
use App\Dtos\ProdukDTOs;
use App\Dtos\UnitPriecesDTOs;
use App\Models\BarangMasuk;
use App\Models\Konversisatuan;
use App\Models\PaymentType;
use App\Models\User;
use App\Services\AgencyService;
use App\Services\BarangMasukService;
use App\Services\GudangService;
use App\Services\JenisProdukService;
use App\Services\JenisSatuanService;
use App\Services\KonversiSatuanService;
use App\Services\ProdukService;
use App\Services\StokService;
use App\Services\UnitPriecesService;
use Database\Seeders\StatusSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProdukTest extends TestCase
{
    use RefreshDatabase;
    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(StatusSeeder::class);
    }
    public function Agencystore()
    {
        $agency = $this->app->make(AgencyService::class);
        $data = [
            "oncard_instansi_id" => 1,
            "kode_instansi" => "xAGY1",
            "nama" => "xPondok Pesantren Al-Munawwir",
            "username" => "xpondok_demo",
            "password" => "xpassword",
            "alamat" => "xAgency 1",
        ];
        $createds =  $agency->create($data);
        return $createds;
    }
    public function GudangStore()
    {
        $service = $this->Agencystore();
        auth()->login(User::find($service['user_id']));
        $service = $this->app->make(GudangService::class);
        $data = [
            "username" => "gudang_demo",
            "password" => "password",
            "nama" => "Gudang Demo",
            "alamat" => "Gudang 1",
            "telepon" => "08123456789",
            "deskripsi" => "Gudang 1",
        ];
        $createds =  $service->create($data);
        return $createds;
    }


    public function testProduk()
    {
        $gudang = $this->GudangStore();
        auth()->login(User::find($gudang['user_id']));
        $jenisProd = $this->app->make(JenisProdukService::class);
        $jenisCreatd = $jenisProd->create([
            'name' => 'Snack',
        ]);

        $satuan = $this->app->make(JenisSatuanService::class);
        $dataSatuan = ["Kilogram", "Gram", "Liter", "Mililiter", "Pcs", "Botol", "Dus", "Kardus", "Kodi", "Lusin", "Rim", "Gross", "Ton", "Kuintal", "Ons", "Pon", "Sendok", "Sendok Teh", "Sendok Makan", "Gelas", "Piring", "Mangkok", "Toples", "Kotak", "Kantong", "Karung", "Bungkus", "Bal", "Batang", "Butir", "Helai", "Lembar", "Buah", "Potong", "Ikat", "Keping", "Kepingan", "Kerat", "Keranjang"];
        foreach ($dataSatuan as $key => $x) {
            $JenisSatuan = $satuan->create([
                'name' =>  $x,
            ]);
        }

        $service = $this->app->make(ProdukService::class);
        $prod = $service->create(ProdukDTOs::fromArray([
            'name' => 'Produk 1',
            'deskripsi' => 'Deskripsi Produk 1',
            'gambar' => 'gambar1.jpg',
            'jenis_produk_id' => $jenisCreatd->id,
            'barcode' => '124123214',
            'satuan_stok' => [
                'satuan_stok_id' => 1,
            ],
        ])->toArray());
        $this->assertNotNull($prod);

        // unit prieces
        $unitPrieces = $this->app->make(UnitPriecesService::class);
        $unitDTOs = UnitPriecesDTOs::fromArray([
            "produks_id" => $prod->id,
            "name" => "satuan",
            "priece" => 10000,
            "jumlah_satan_jual" => 1,
            "jenis_satuan_jual_id" => 1,
            "diskon" => 0,
        ]);
        $unit = $unitPrieces->fromCreatd($unitDTOs)
            ->store();
        $this->assertNotNull($unit);

        // update produk
        $update = $service->update($prod->id, ProdukDTOs::fromArray([
            'name' => 'update Produk 1',
            'deskripsi' => 'update Deskripsi Produk 1',
            'gambar' => 'gambar1.jpg',
            'jenis_produk_id' => $jenisCreatd->id,
            'barcode' => '124123214',
            'satuan_stok' => [
                'satuan_stok_id' => 1,
            ],
        ])->toArray());
        $this->assertNotNull($update);
        // update unit prieces
        $unitDTOsUpdate = UnitPriecesDTOs::fromArray([
            "produks_id" => $prod->id,
            "name" => "satuan",
            "priece" => 50000,
            "jumlah_satan_jual" => 1,
            "jenis_satuan_jual_id" => 1,
            "diskon" => 0,
        ])->initModel($unit->id);
        $unit = $unitPrieces->fromUpdate($unitDTOsUpdate)
            ->update();
        $this->assertNotNull($unit);
        $delete = $service->delete($prod->id);
        $this->assertTrue($delete);

        // konversi test //////////////////////////////////////////////////////////////////////////////////////////
        $service = $this->app->make(ProdukService::class);
        $prod = $service->create(ProdukDTOs::fromArray([
            'name' => 'Produk 1',
            'deskripsi' => 'Deskripsi Produk 1',
            'gambar' => 'gambar1.jpg',
            'jenis_produk_id' => $jenisCreatd->id,
            'barcode' => '124123214',
            'satuan_stok' => [
                'satuan_stok_id' => 1,
            ],
        ])->toArray());
        $this->assertNotNull($prod);
        $unitPrieces = $this->app->make(UnitPriecesService::class);
        $unit = $unitPrieces->fromCreatd(UnitPriecesDTOs::fromArray([
            "produks_id" => $prod->id,
            "name" => "satuan",
            "priece" => 10000,
            "jumlah_satan_jual" => 1,
            "jenis_satuan_jual_id" => 1,
            "diskon" => 0,
        ]))
            ->store();
        $this->assertNotNull($unit);

        // satuan konversi produk
        //    //////////////////////////////////////////////
        $konversi = $this->app->make(KonversiSatuanService::class);
        $konversiDTOs = $konversi->fromDTOs(KonversiSatuanDTOs::fromArray([
            'produks_id' => $prod->id,
            'satuan_id' => Satuan::DUS,
            'satuan_konversi_id' => $prod->satuanStok->getSatuanStokId(),
            'nilai_konversi' => (float) 10,
        ]))->create();
        $this->assertNotNull($konversiDTOs);


        $konversi = $this->app->make(KonversiSatuanService::class);
        $konversiDTOs = $konversi->fromDTOs(KonversiSatuanDTOs::fromArray([
            'produks_id' => $prod->id,
            'satuan_id' => Satuan::LUSIN,
            'satuan_konversi_id' => $prod->satuanStok->getSatuanStokId(),
            'nilai_konversi' => (float) 12,
        ]))->create();
        $this->assertNotNull($konversiDTOs);

        //----------------- cek konversi -----------------------------------    
        $konversion = $this->app->make(KonversiSatuanService::class);
        $konversionDTOs = $konversion->convertToSatuanStok(
            produksId: $prod->id, // konversi dari produk id 
            from: Satuan::DUS, // konversi ke satuan dus (Dus) dengan nilai 10
            qty: 5, // konversi 5
        );
        //nilai konversi harus nya
        // 5 * 10 = 50
        $this->assertEquals(50, $konversionDTOs['konversi']);

        $konversion = $this->app->make(KonversiSatuanService::class);
        $konversionDTOs = $konversion->convert(
            produksId: $prod->id, // konversi dari produk id 
            from: Satuan::LUSIN, // konversi ke satuan dus (Dus) dengan nilai 10
            to: Satuan::KILOGRAM, // konversi ke satuan lusin (Lusin) dengan nilai 12
            qty: 5, // konversi 5
        );
        //nilai konversi harus nya
        // 5 * 10 = 50
        $this->assertEquals(60, $konversionDTOs['konversi']);
        /////////////////////////////////////////////////// /////////////////////////////


        //  barang masuk //////////////////////////////////////////////////////////////////////////////////////////
        /**
         * 1. barang masuk
         */
        $bService = $this->app->make(BarangMasukService::class);
        $masuk =   $bService->fromDTOs((new  BarangMasukDTOs(
            produks_id: $prod->id,
            supplier_id: null,
            harga_beli: 10000,
            jumlah_barang_masuk: 10,
            satuan_beli_id: Satuan::DUS,
        )))->create();
        $findBarangMasuk = BarangMasuk::find($masuk->id);
        $this->assertEquals(10, $findBarangMasuk->jumlah_barang_masuk);
        $this->assertEquals(Satuan::DUS, $findBarangMasuk->satuan_beli_id);
        $this->assertEquals(0, $findBarangMasuk->jumlah_sebelumnya);
        $this->assertEquals($prod->satuanStok->getSatuanStokId(), $findBarangMasuk->satuan_sebelumnya_id);
        // cek stok
        $stok = $this->app->make(StokService::class);
        $stokFind = $stok->findStokByProdukId($prod->id);
        $this->assertEquals(100, $stokFind->jumlah);
        $this->assertEquals($prod->satuanStok->getSatuanStokId(), $stokFind->satuan_id);
        $this->assertEquals(0, $stokFind->jumlah_sebelumnya);
        $this->assertEquals($prod->satuanStok->getSatuanStokId(), $stokFind->satuan_sebelumnya_id);
        /**
         * ---------------------------------------------------------
         * 2. barang masuk
         */
        $bService = $this->app->make(BarangMasukService::class);
        $masuk =   $bService->fromDTOs((new  BarangMasukDTOs(
            produks_id: $prod->id,
            supplier_id: null,
            harga_beli: 10000,
            jumlah_barang_masuk: 10,
            satuan_beli_id: Satuan::LUSIN,
        )))->create();
        $findBarangMasuk = BarangMasuk::find($masuk->id);
        $this->assertEquals(10, $findBarangMasuk->jumlah_barang_masuk);
        $this->assertEquals(Satuan::LUSIN, $findBarangMasuk->satuan_beli_id);
        $this->assertEquals(100, $findBarangMasuk->jumlah_sebelumnya);
        $this->assertEquals($prod->satuanStok->getSatuanStokId(), $findBarangMasuk->satuan_sebelumnya_id);
        // cek stok
        $stok = $this->app->make(StokService::class);
        $stokFind = $stok->findStokByProdukId($prod->id);
        $this->assertEquals(220, $stokFind->jumlah);
        $this->assertEquals($prod->satuanStok->getSatuanStokId(), $stokFind->satuan_id);
        $this->assertEquals(100, $stokFind->jumlah_sebelumnya);
        $this->assertEquals($prod->satuanStok->getSatuanStokId(), $stokFind->satuan_sebelumnya_id);

        /**
         * ---------------------------------------------------------
         * 3. barang 2 revisi masuk
         */
        $bService = $this->app->make(BarangMasukService::class);
        $masukRev =   $bService->fromDTOs((new  BarangMasukDTOs(
            id: $masuk->id,
            produks_id: $prod->id,
            supplier_id: null,
            harga_beli: 10000,
            jumlah_barang_masuk: 10,
            satuan_beli_id: Satuan::DUS,
        )))->create();
        $findBarangMasuk = BarangMasuk::find($masuk->id);
        $this->assertEquals(10, $findBarangMasuk->jumlah_barang_masuk);
        $this->assertEquals(Satuan::DUS, $findBarangMasuk->satuan_beli_id);
        $this->assertEquals(100, $findBarangMasuk->jumlah_sebelumnya);
        $this->assertEquals($prod->satuanStok->getSatuanStokId(), $findBarangMasuk->satuan_sebelumnya_id);
        // cek stok
        $stok = $this->app->make(StokService::class);
        $stokFind = $stok->findStokByProdukId($prod->id);
        $this->assertEquals(200, $stokFind->jumlah);
        $this->assertEquals($prod->satuanStok->getSatuanStokId(), $stokFind->satuan_id);
        $this->assertEquals(100, $stokFind->jumlah_sebelumnya);
        $this->assertEquals($prod->satuanStok->getSatuanStokId(), $stokFind->satuan_sebelumnya_id);
        /**
         * -----------------------------------------------------------------------------------------------  
         */
        /**
         * ---------------------------------------------------------
         * 3. barang 2 cencel
         */
        $bService = $this->app->make(BarangMasukService::class);
        $bService->cancel($masuk->id);
        $findBarangMasuk = BarangMasuk::find($masuk->id);
        $this->assertNull($findBarangMasuk);
        // cek stok
        $stok = $this->app->make(StokService::class);
        $stokFind = $stok->findStokByProdukId($prod->id);
        $this->assertEquals(100, $stokFind->jumlah);
        $this->assertEquals($prod->satuanStok->getSatuanStokId(), $stokFind->satuan_id);
        $this->assertEquals(100, $stokFind->jumlah_sebelumnya);
        $this->assertEquals($prod->satuanStok->getSatuanStokId(), $stokFind->satuan_sebelumnya_id);
    }
}
