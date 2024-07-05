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
        Schema::create('trx_types', function (Blueprint $table) {
            $table->increments("id");
            $table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->unsignedInteger('gudang_id');
            $table->unsignedBigInteger('users_create_id');
            $table->foreign('users_create_id')->references('id')->on('users')->onDelete('cascade');
            $table->string("name");
            $table->string("descriptions");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jenis_produks');
    }
};
