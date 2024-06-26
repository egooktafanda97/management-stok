<?php

namespace Database\Seeders;

use App\Constant\Status;
use App\Models\ConfigGudang;
use App\Models\Gudang;
use App\Models\PaymentType;
use App\Models\TrxTypes;
use App\Models\User;
use App\Services\JenisProdukService;
use App\Services\JenisSatuanService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DataMasterSeeder extends Seeder
{
    public function __construct(
        public JenisSatuanService $jenisSatuanService,
        public JenisProdukService $jenisProdukService,
    ) {
    }
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        auth()->login(User::whereUsername('gudang_demo')->first());
        $payTypes = ['CASH', 'DEBS', 'ONCARD'];
        foreach ($payTypes as $payType) {
            PaymentType::create([
                'agency_id' => 1,
                'gudang_id' => Gudang::whereUserId(auth()->user()->id)->first()->id,
                'name' => $payType,
                'alias' => $payType,
                'type' => $payType,
                'props' => '[]',
                'description' => $payType,
                'icon' => $payType,
                'status_id' => Status::ACTIVE,
            ]);
        }

        $trxTypes = ['Sale', 'Purchase', 'Transfer', 'Withdrawal', 'Deposit', 'Refund', 'Expense', 'Income', 'Loan', 'TransferBalance', 'melting', 'PAYMENT'];
        foreach ($trxTypes as $trxType) {
            TrxTypes::create([
                'agency_id' => Gudang::whereUserId(auth()->user()->id)->first()->agency_id,
                'gudang_id' => Gudang::whereUserId(auth()->user()->id)->first()->id,
                'users_create_id' => auth()->user()->id,
                'name' => $trxType,
                'descriptions' => $trxType,
            ]);
        }

        ConfigGudang::set(
            key: 'pph',
            value: 10,
            agency_id: Gudang::whereUserId(auth()->user()->id)->first()->agency_id,
            gudang_id: Gudang::whereUserId(auth()->user()->id)->first()->id,
        );

        $jenis = [
            'Makanan',
            'Minuman',
            'Snack',
            'Pakaian',
            'Elektronik',
        ];
        foreach ($jenis as $key => $x) {
            $this->jenisProdukService->create([
                'name' =>  $x,
            ]);
        }

        /**
         * satuan master
         */
        $dataSatuan = ["Kilogram", "Gram", "Liter", "Mililiter", "Pcs", "Botol", "Dus", "Kardus", "Kodi", "Lusin", "Rim", "Gross", "Ton", "Kuintal", "Ons", "Pon", "Sendok", "Sendok Teh", "Sendok Makan", "Gelas", "Piring", "Mangkok", "Toples", "Kotak", "Kantong", "Karung", "Bungkus", "Bal", "Batang", "Butir", "Helai", "Lembar", "Buah", "Potong", "Ikat", "Keping", "Kepingan", "Kerat", "Keranjang", "Pack"];
        foreach ($dataSatuan as $key => $x) {
            $this->jenisSatuanService->create([
                'name' =>  $x,
            ]);
        }
    }
}
