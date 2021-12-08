<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogJurnal extends Model
{
    protected $table = 'log-jurnal';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id-user',
        'transaksi',
        'tipe',
        'created_at',
        'jurnal-old',
        'jurnal-now',
        'keterangan',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
