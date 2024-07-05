<?php

namespace Tests\Unit;

use App\Constant\PayType;
use App\Constant\Satuan;
use App\Dtos\TransactionDTOs;
use App\Models\Produk;
use App\Models\User;
use App\Services\TrxService;
use App\Services\UnitPriecesService;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TtxTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(DatabaseSeeder::class);
    }
    public function testTransaction()
    {
        auth()->login(User::whereUsername('kasir_demo')->first());
        $service = $this->app->make(TrxService::class);
        $unitPrieces = $this->app->make(UnitPriecesService::class);
        $trxDTos = new TransactionDTOs();
        $trxDTos->OrderItems(
            produks_id: 1,
            qty: 2,
            satuan: $unitPrieces->findByIdSatuanAndProdukId(
                jenisSatuanJualId: Satuan::PACK,
                produkId: 1
            )
        );
        $transaction = $service->fromDTOs(
            $trxDTos->order(
                pelanggan_id: 1,
                total_uang_pelanggan: 100000,
                payment_type_id: PayType::DEBS,
                diskon: 10,
                pph: true,
            )
        )
            ->middle()
            ->trxProccessing();
        // dd($transaction);

    }
}
