<?php

namespace App\Http\Controllers;

use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Tipe;
use App\Kategori;
use App\Akun;

class AkunController extends Controller
{
    public function index(){
        $kategori = Kategori::whereNotNull('parent')->get();
        $akun = Akun::with('getTipe', 'getKategori')->get();

        return view('akun', ['kategori'=>$kategori, 'akun'=>$akun]);
    }

    public function store(Request $request){
        try{
            $akun_baru = new Akun($request->all());
            $akun_baru->saldo=0;
            $akun_baru->save();
        }catch(QueryException $exception){
            $this->flashError($exception->getMessage());
            return back();
        }

        $this->flashSuccess('Data Akun Berhasil Ditambahkan');
        return back();
    }

    public function update(Request $request, $id){
        try{
            $akun = Akun::findOrFail($id);
            $akun->fill($request->all());
            $akun->save();
        }catch(QueryException $exception){
            $this->flashError($exception->getMessage());
            return back();
        }
        
        $this->flashSuccess('Data Akun Berhasil Diubah');
        return back();
    }

    public function destroy($id){
        try {
            $akun = Akun::findOrFail($id);
            $akun->delete();
        }catch (QueryException $exception) {
            $this->flashError($exception->getMessage());
            return back();
        }

        $this->flashSuccess('Data Akun Berhasil Dihapus');
        return back();
    }
}
