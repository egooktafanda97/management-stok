<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('general_actor', function (Blueprint $table) {
            $table->bigIncrements("id");
            $table->unsignedInteger('oncard_instansi_id')->nullable();
            $table->unsignedInteger('agency_id');
            $table->foreign('agency_id')->references('id')->on('agency');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedInteger('oncard_user_id')->nullable();
            $table->unsignedInteger('oncard_account_number')->nullable();
            $table->string('nama');
            $table->enum('user_type', ['siswa', 'general', 'merchant', 'agency', 'owner']);
            $table->date('sync_date')->nullable();
            $table->text('detail')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kasir');
    }
};
