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
        Schema::create('transaksi', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->unsignedInteger('gudang_id');
            $table->foreign('gudang_id')->references('id')->on('gudang')->onDelete('cascade');
            $table->unsignedBigInteger('kasir_id');
            $table->foreign('kasir_id')->references('id')->on('kasir')->onDelete('cascade');
            $table->unsignedBigInteger('user_kasir_id');
            $table->foreign('user_kasir_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('user_buyer_id');
            $table->foreign('user_buyer_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('invoice_id');
            $table->foreign('invoice_id')->references('id')->on('invoices')->onDelete('cascade');
            $table->date('tanggal');
            $table->integer('diskon')->default(0); // presentase
            $table->decimal('total_diskon')->default(0); // presentase
            $table->integer('tax')->default(0); // presentase
            $table->decimal('tax_deduction')->default(0); // nominal
            $table->integer('total_gross');
            $table->integer('sub_total');
            $table->unsignedInteger('payment_type_id');
            $table->foreign('payment_type_id')->references('id')->on('payment_types')->onDelete('cascade');
            $table->unsignedInteger('status_id')->default(1);
            $table->foreign('status_id')->references('id')->on('status')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};
