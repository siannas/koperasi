<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogJurnalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('log-jurnal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id-user')->unsigned();
            $table->tinyInteger('tipe')->unsigned();
            $table->string('transaksi',20);
            $table->string('jurnal-old',1024)->nullable();
            $table->string('jurnal-now',1024)->nullable();
            $table->string('keterangan');
            $table->foreign('id-user')->references('id')->on('users');
            $table->dateTime('created_at', $precision = 0)->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('log-jurnal');
    }
}
