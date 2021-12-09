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
            $table->integer('id-akun')->unsigned();
            $table->integer('id-tipe')->unsigned();
            $table->decimal('saldo', $precision = 13, $scale = 2);
            $table->date('tanggal');
            $table->foreign('id-kategori')->references('id-kategori')->on('akun')->cascadeOnUpdate();
            $table->foreign('id-akun')->references('id')->on('akun')->cascadeOnUpdate()->restrictOnDelete();
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
