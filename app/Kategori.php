<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Kategori extends Model
{
    protected $table = 'kategori';

    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
   protected $fillable = [
       'kategori', 
       'tipe-pendapatan',
       'parent',
   ];

   /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
   protected $hidden = [
   ];

    public function getAkun(){
        return $this->hasMany(Akun::class, 'id-kategori');
    }
}
