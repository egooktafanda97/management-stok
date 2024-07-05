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
        Schema::create('payment_types', function (Blueprint $table) {
            $table->increments("id");
            $table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->unsignedInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->string('name');
            $table->string('alias')->nullable();
            $table->string('type');
            $table->text('props')->nullable();
            $table->string('description')->nullable();
            $table->string('icon')->nullable();
            $table->unsignedInteger('status_id');
            $table->foreign('status_id')->references('id')->on('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_types');
    }
};
