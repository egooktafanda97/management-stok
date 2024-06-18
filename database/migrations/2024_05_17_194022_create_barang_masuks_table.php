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

        Schema::create('konversisatuan', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('agency_id');
            $table->unsignedInteger('gudang_id');
            $table->unsignedBigInteger('user_created_id');
            $table->unsignedBigInteger('produks_id');
            $table->unsignedInteger('satuan_id');
            $table->unsignedInteger('satuan_konversi_id');
            $table->double('nilai_konversi');
            $table->unsignedInteger('status_id')->default(1);
            $table->timestamps();

            $table->foreign('agency_id')->references('id')->on('agency')->onDelete('cascade');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->foreign('user_created_id')->references('id')->on('users');
            $table->foreign('produks_id')->references('id')->on('produks');
            $table->foreign('satuan_id')->references('id')->on('jenis_satuans')->onDelete('cascade');
            $table->foreign('satuan_konversi_id')->references('id')->on('jenis_satuans')->onDelete('cascade');
        });

        Schema::create('barang_masuk', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->unsignedInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->unsignedBigInteger("user_created_id");
            $table->foreign('user_created_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger("produks_id");
            $table->foreign('produks_id')->references('id')->on('produks')->onDelete('cascade');
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->foreign('supplier_id')->references('id')->on('suppliers');
            $table->integer('harga_beli');
            $table->integer('jumlah_barang_masuk');
            $table->unsignedInteger('satuan_beli_id');
            $table->foreign('satuan_beli_id')->references('id')->on('jenis_satuans');
            $table->integer('jumlah_sebelumnya');
            $table->unsignedInteger('satuan_sebelumnya_id');
            $table->foreign('satuan_sebelumnya_id')->references('id')->on('jenis_satuans');
            $table->unsignedInteger('status_id')->default(1);
            $table->foreign('status_id')->references('id')->on('status');
            $table->timestamps();
        });

        Schema::create('stoks', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->unsignedInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->unsignedBigInteger('produks_id');
            $table->foreign('produks_id')->references('id')->on('produks')->onDelete('cascade');
            $table->integer('jumlah')->default(0);
            $table->unsignedInteger('satuan_id');
            $table->foreign('satuan_id')->references('id')->on('jenis_satuans');
            $table->integer('jumlah_sebelumnya');
            $table->unsignedInteger('satuan_sebelumnya_id');
            $table->foreign('satuan_sebelumnya_id')->references('id')->on('jenis_satuans');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('barang_masuks');
        Schema::dropIfExists('barang_masuk');
        Schema::dropIfExists('konversisatuan');
        Schema::dropIfExists('log_barang_masuk');
        Schema::dropIfExists('stoks');
    }
};
