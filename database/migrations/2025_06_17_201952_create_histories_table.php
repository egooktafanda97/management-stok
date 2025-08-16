<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('agency_id');
            $table->unsignedInteger('gudang_id');
            $table->unsignedBigInteger('kasir_id')->nullable();
            $table->unsignedBigInteger('user_kasir_id')->nullable();
            $table->unsignedBigInteger('user_trx_id')->nullable();
            $table->unsignedBigInteger('invoice_id');
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->decimal('saldo_awal', 15, 2);
            $table->decimal('saldo_akhir', 15, 2);
            $table->decimal('total', 15, 2);
            $table->unsignedInteger('status_id')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('histories');
    }
};
