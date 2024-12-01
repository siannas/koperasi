<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Saldo extends Model
{
    use \Staudenmeir\LaravelUpsert\Eloquent\HasUpsertQueries;
    
    protected $table = 'saldo';
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id-tipe',  // tipe 0: khusus saldo awal aja
        'id-akun',
        'id-kategori',
        'saldo_awal',
        'saldo',
        'tanggal',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
