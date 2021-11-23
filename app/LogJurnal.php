<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LogJurnal extends Model
{
    protected $table = 'log-jurnal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id-debit',
        'id-kredit',
        'id-user',
        'id-jurnal',
        'no-ref', 
        'debet',
        'kredit',
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
