<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAkunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('akun', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id-kategori')->unsigned()->nullable();
            $table->integer('id-tipe')->unsigned();
            $table->string('no-akun',18)->unique();
            $table->string('nama-akun',50);
            $table->unsignedDecimal('saldo', $precision = 13, $scale = 2);
            $table->foreign('id-kategori')->references('id')->on('kategori')->nullOnDelete();
            $table->foreign('id-tipe')->references('id')->on('tipe');
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
        Schema::dropIfExists('akun');
    }
}
