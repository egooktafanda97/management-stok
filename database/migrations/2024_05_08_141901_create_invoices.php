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
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');

            $table->unsignedInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');

            $table->unsignedBigInteger('users_merchant_id');
            $table->foreign('users_merchant_id')->references('id')->on('users')->onDelete('cascade');

            $table->unsignedBigInteger('users_trx_id');
            $table->foreign('users_trx_id')->references('id')->on('users')->onDelete('cascade');

            $table->string("invoice_id", 100)->unique()->index();
            $table->unsignedInteger('trx_types_id');
            $table->foreign('trx_types_id')->references('id')->on('trx_types');

            $table->unsignedInteger('payment_type_id');
            $table->foreign('payment_type_id')->references('id')->on('payment_types');

            $table->dateTime("dates");
            $table->unsignedBigInteger('status_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
