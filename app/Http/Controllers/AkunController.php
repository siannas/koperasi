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
        $kategori = Kategori::all();
        $akun = Akun::with('getTipe', 'getKategori')->get();

        return view('akun', ['kategori'=>$kategori, 'akun'=>$akun]);
    }

    public function store(Request $request){
        $akun_baru = new Akun($request->all());
        $akun_baru->saldo=0;
        $akun_baru->save();

        $this->flashSuccess('Data Akun Berhasil Ditambahkan');
        return back();
    }

    public function update(Request $request, $id){
        $akun = Akun::findOrFail($id);
        $akun->fill($request->all());
        dd($request->all());
        // $akun_baru->save();

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
