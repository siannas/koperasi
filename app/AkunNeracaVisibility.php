<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AkunNeracaVisibility extends Model
{
    protected $table = 'akun_neraca_visibility';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id-akun', 
        'id-tipe', 
        'year', 
        'show',
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
    public function getAkun(){
        return $this->belongsTo(Akun::class, 'id-akun');
    }
}
