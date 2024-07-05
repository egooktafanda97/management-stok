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
        Schema::create('stok_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->$table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->foreign('user_created_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('stok_histories');
    }
};
