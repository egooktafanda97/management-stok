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
        Schema::create('barang_keluar', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->unsignedInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->unsignedBigInteger("user_created_id");
            $table->foreign('user_created_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->unsignedBigInteger("produks_id");
            $table->foreign('produks_id')->references('id')->on('produks')->onDelete('cascade');
            $table->integer('harga_satuan')->nullable();
            $table->integer('jumlah_barang_keluar');
            $table->unsignedInteger('satuan_keluar_id');
            $table->foreign('satuan_keluar_id')->references('id')->on('jenis_satuans');
            $table->integer('jumlah_sebelumnya');
            $table->unsignedInteger('satuan_sebelumnya_id');
            $table->foreign('satuan_sebelumnya_id')->references('id')->on('jenis_satuans');
            $table->string("tipe", 10)->nullable();
            $table->unsignedInteger('status_id')->default(1);
            $table->foreign('status_id')->references('id')->on('status');
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_keluars');
    }
};
