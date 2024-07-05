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
        Schema::create('unit_priece', function (Blueprint $table) {
            $table->bigIncrements("id");
            //    core data
            $table->unsignedBigInteger('user_created_id');
            $table->foreign('user_created_id')->references('id')->on('users');
            $table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->unsignedInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->unsignedBigInteger('produks_id');
            $table->foreign('produks_id')->references('id')->on('produks');
            // ----------------------
            $table->string('name')->nullable();

            $table->integer('priece');
            $table->double('priece_decimal')->nullable();

            // $table->unsignedInteger('jumlah_satan_jual');

            $table->unsignedInteger('jenis_satuan_jual_id');
            $table->foreign('jenis_satuan_jual_id')->references('id')->on('jenis_satuans');

            $table->integer('diskon')->default(0);

            // status
            $table->unsignedInteger('status_id');
            $table->foreign('status_id')->references('id')->on('status');

            $table->unsignedBigInteger('user_update_id')->nullable();
            $table->foreign('user_update_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hargas');
    }
};
