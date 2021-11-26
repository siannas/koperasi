<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Akun extends Model
{
    protected $table = 'akun';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id-kategori', 
        'no-akun', 
        'id-tipe', 
        'nama-akun',
        'saldo',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function getTipe(){
        return $this->belongsTo(Tipe::class, 'id-tipe');
    }
    public function getKategori(){
        return $this->belongsTo(Kategori::class, 'id-kategori');
    }
}
