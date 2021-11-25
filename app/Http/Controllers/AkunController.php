<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Tipe;
use App\Kategori;
use App\Akun;

class AkunController extends Controller
{
    public function index(){
        $tipe = Tipe::all();
        $kategori = Kategori::all();
        $akun = Akun::all();

        return view('akun', ['tipe'=>$tipe, 'kategori'=>$kategori, 'akun'=>$akun]);
    }

    public function store(Request $request){
        $akun_baru = new Akun($request->all());
        $akun_baru->saldo=0;
        // $akun_baru->save();

        $this->flashSuccess('Data Akun Berhasil Ditambahkan');
        return back();
        // return redirect()->back();
    }
}
