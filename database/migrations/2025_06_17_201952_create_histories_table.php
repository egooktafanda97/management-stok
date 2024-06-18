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
            $table->bigIncrements("id");
            $table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->unsignedInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->unsignedBigInteger('kasir_id')->nullable();
            $table->foreign('kasir_id')->references('id')->on('kasir')->onDelete('cascade');
            $table->unsignedBigInteger('user_kasir_id')->nullable();
            $table->foreign('user_kasir_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('user_trx_id')->nullable();
            $table->foreign('user_trx_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('jumlah');
            $table->integer('saldo_awal');
            $table->integer('saldo_akhir');
            $table->integer('total');
            $table->unsignedInteger('status_id')->default(1);
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
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
