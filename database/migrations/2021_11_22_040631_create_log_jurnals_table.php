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
            $table->integer('id-jurnal')->unsigned()->nullable();
            $table->integer('id-user')->unsigned();
            $table->integer('id-debit')->unsigned();
            $table->integer('id-kredit')->unsigned();
            $table->string('no-ref', 18);
            $table->unsignedDecimal('debit', $precision = 13, $scale = 2);
            $table->unsignedDecimal('kredit', $precision = 13, $scale = 2);
            $table->string('keterangan', 100);
            $table->foreign('id-debit')->references('id')->on('akun');
            $table->foreign('id-kredit')->references('id')->on('akun');
            $table->foreign('id-user')->references('id')->on('users');
            $table->foreign('id-jurnal')->references('id')->on('jurnal');
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
        Schema::dropIfExists('log-jurnal');
    }
}
