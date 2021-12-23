<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJurnalsTable extends Migration
{
    public $timestamps = ["created_at"]; //only want to used created_at column

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jurnal', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id-tipe')->unsigned();
            $table->integer('id-debit')->unsigned();
            $table->integer('id-kredit')->unsigned();
            $table->string('no-ref', 18)->nullable();
            $table->decimal('debit', $precision = 13, $scale = 2);
            $table->decimal('kredit', $precision = 13, $scale = 2);
            $table->string('keterangan', 100);
            $table->date('tanggal');
            $table->foreign('id-tipe')->references('id')->on('tipe');
            $table->foreign('id-debit')->references('id')->on('akun');
            $table->foreign('id-kredit')->references('id')->on('akun');
            $table->timestamps();
            $table->boolean('validasi')->default(0);
            $table->string('by-role', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('jurnal');
    }
}
