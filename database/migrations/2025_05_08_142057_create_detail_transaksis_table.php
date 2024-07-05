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
        Schema::create('detail_transaksi', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->unsignedInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->unsignedBigInteger('user_kasir_id');
            $table->foreign('user_kasir_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('user_buyer_id');
            $table->foreign('user_buyer_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->unsignedBigInteger('transaksi_id');
            $table->foreign('transaksi_id')->references('id')->on('transaksi')->onDelete('cascade');
            $table->unsignedBigInteger('produks_id');
            $table->foreign('produks_id')->references('id')->on('produks')->onDelete('cascade');
            $table->unsignedBigInteger('unit_priece_id');
            $table->foreign('unit_priece_id')->references('id')->on('unit_priece');
            $table->unsignedInteger('satuan_id');
            $table->foreign('satuan_id')->references('id')->on('jenis_satuans');

            $table->integer('priece');
            $table->double('priece_decimal');

            $table->integer('jumlah');
            $table->integer('total');
            $table->integer('diskon')->default(0);
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
        Schema::dropIfExists('detail_transaksi');
    }
};
