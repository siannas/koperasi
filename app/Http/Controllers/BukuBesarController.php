<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Jurnal;
use App\Akun;

class BukuBesarController extends Controller
{
    public function index(Request $request){
        $tipe=$request->get('tipe');
        $akun=Akun::where('id-tipe', $tipe->id)->get();
        $jurnal=[];
        
        return view('bukuBesar', ['currentTipe'=>$tipe, 'akun'=>$akun, 'curAkun'=>new Akun(),'jurnal'=>$jurnal]);
    }

    public function filter(Request $request){
        $tipe=$request->get('tipe');
        $akun=Akun::where('id-tipe', $tipe->id)->get();
        
        $tipePen = Akun::with(['getKategori' => function($query) { 
                $query->select('id','tipe-pendapatan');
                }])->where('akun.id', $request->akun)->first();

        $jurnal = Jurnal::where('id-tipe', $tipe->id)
            ->where('id-debit', $request->akun)
            ->orWhere('id-kredit', $request->akun)->get();

        return view('bukuBesar', ['currentTipe'=>$tipe, 'akun'=>$akun, 'curAkun'=>$tipePen, 'jurnal'=>$jurnal]);
    }
}
