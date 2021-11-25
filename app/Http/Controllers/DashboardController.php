<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tipe;

class DashboardController extends Controller
{
    public function dashboard(){
        $tipe = Tipe::all();

        return view('dashboard', ['tipe'=>$tipe]);
    }
}
