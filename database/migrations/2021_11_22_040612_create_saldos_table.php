<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSaldosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('saldo', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id-kategori')->unsigned();
            $table->string('no-akun',18);
            $table->integer('id-tipe')->unsigned();
            $table->unsignedDecimal('saldo', $precision = 13, $scale = 2);
            $table->date('tanggal');
            $table->foreign('id-kategori')->references('id-kategori')->on('akun')->cascadeOnUpdate();
            $table->foreign('no-akun')->references('no-akun')->on('akun')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreign('id-tipe')->references('id-tipe')->on('akun')->cascadeOnUpdate()->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('saldo');
    }
}
