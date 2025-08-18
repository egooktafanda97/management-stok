<?php

namespace Database\Seeders;

use App\Constant\PayType;
use App\Constant\Satuan;
use App\Dtos\TransactionDTOs;
use App\Models\User;
use App\Services\TrxService;
use App\Services\UnitPriecesService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        auth()->login(User::whereUsername('kasir_demo')->first());
        $service = app()->make(TrxService::class);
        $unitPrieces = app()->make(UnitPriecesService::class);
        $trxDTos = new TransactionDTOs();
        $trxDTos->OrderItems(
            produks_id: 1,
            qty: 2,
            satuan: $unitPrieces->findByIdSatuanAndProdukId(
                jenisSatuanJualId: Satuan::PACK,
                produkId: 1
            )
        );
        // $transaction = $service->fromDTOs(
        //     $trxDTos->order(
        //         pelanggan_id: 1,
        //         total_uang_pelanggan: 100000,
        //         payment_type_id: PayType::DEBS,
        //         diskon: 10,
        //         pph: true,
        //     )
        // )
        //     ->middle()
        //     ->trxProccessing();
    }
}
