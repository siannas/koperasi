<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tipe extends Model
{
    protected $table = 'tipe';
    
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'tipe',
        'slug',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
}
