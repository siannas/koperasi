<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Jurnal extends Model
{
    protected $table = 'jurnal';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id-tipe',
        'id-debit',
        'id-kredit',
        'no-ref', 
        'debit',
        'kredit',
        'tanggal',
        'keterangan',
        'validasi',
        'by-role',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];

    public function akunDebit(){
        return $this->belongsTo(Akun::class, 'id-debit');
    }

    public function akunKredit(){
        return $this->belongsTo(Akun::class, 'id-kredit');
    }
}
