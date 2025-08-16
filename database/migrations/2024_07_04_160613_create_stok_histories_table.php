<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stok_history', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('agency_id');
            $table->unsignedBigInteger('user_created_id');
            $table->unsignedInteger('gudang_id');
            $table->unsignedBigInteger('produks_id');
            $table->integer('jumlah')->default(0);
            $table->unsignedInteger('satuan_id');
            $table->integer('jumlah_sebelumnya');
            $table->unsignedInteger('satuan_sebelumnya_id');
            $table->text('keterangan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stok_history');
    }
};
