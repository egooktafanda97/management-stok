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
        Schema::table('gudang', function (Blueprint $table) {
            $table->string('oncard_api_key'); // Gantilah tipe data dan nama kolom sesuai kebutuhan
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('gudang', function (Blueprint $table) {
            $table->dropColumn('oncard_api_key'); // Nama kolom yang sama seperti di atas
        });
    }
};
