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
        Schema::create('rak', function (Blueprint $table) {
            $table->increments("id");
            $table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->unsignedInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->integer('code')->nullable();
            $table->integer('barcode')->nullable();
            $table->string('nama');
            $table->integer('kapasitas');
            $table->timestamps();
        });
        Schema::create('produks', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->unsignedInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->unsignedBigInteger("user_id");
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('name');
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->default('default.jpg');
            $table->unsignedBigInteger('jenis_produk_id');
            $table->foreign('jenis_produk_id')->references('id')->on('jenis_produks')->onDelete('cascade');
            $table->string('barcode', 100)->nullable();
            $table->unsignedInteger('rak_id')->nullable();
            $table->unsignedInteger('status_id');
            $table->foreign('status_id')->references('id')->on('status');
            $table->timestamps();
        });

        Schema::create('produks_config', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->unsignedInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->unsignedBigInteger('produks_id');
            $table->foreign('produks_id')->references('id')->on('produks');

            // jenis satuan stok
            $table->unsignedInteger('satuan_stok_id');
            $table->foreign('satuan_stok_id')->references('id')->on('jenis_satuans');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produks');
        Schema::dropIfExists('rak');
    }
};
