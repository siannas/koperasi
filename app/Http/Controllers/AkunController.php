<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Tipe;
use App\Kategori;
use App\Akun;
use App\Saldo;

class AkunController extends Controller
{
    public function index(){
        $kategori = Kategori::whereNotNull('parent')->get();

        $subQuery = Saldo::select('id-kategori','id-akun','id-tipe','saldo as saldoawal')
            ->whereIn('id', Saldo::select(DB::raw('min(id) as id'))
                ->groupBy('id-kategori','id-akun','id-tipe')
                ->orderBy('id','ASC')
        );

        $akun = Akun::with('getTipe', 'getKategori')
            ->select('*', 'subquery.saldoawal')
            ->joinSub($subQuery, 'subquery', function ($join)
                {
                    $join->on('subquery.id-akun', '=', 'akun.id')
                        ->on('subquery.id-tipe', '=', 'akun.id-tipe')
                        ->on('subquery.id-kategori', '=', 'akun.id-kategori');
                })
            ->get();
        return view('akun', ['kategori'=>$kategori, 'akun'=>$akun]);
    }

    public function store(Request $request){
        try{
            $akun_baru = new Akun($request->all());
            $akun_baru->save();
        }catch(QueryException $exception){
            $this->flashError($exception->getMessage());
            return back();
        }

        $this->flashSuccess('Data Akun Berhasil Ditambahkan');
        return back();
    }

    public function update(Request $request, $id){
        DB::beginTransaction();
        try{
            $saldoawal = Saldo::select('saldo')
                ->where('id-akun', '=', $id)
                ->where('id-tipe', '=', $request->input('id-tipe'))
                ->where('id-kategori', '=', $request->input('id-kategori'))
                ->orderBy('id','ASC')
                ->first();
        
            if(!$saldoawal)
            {
                throw new Exception("Saldo Awal Tidak Ditemukan");
            }
            
            $selisih = $request->input('saldo') - $saldoawal->saldo;

            if($selisih != 0) 
            {
                Saldo::where('id-akun', '=', $id)
                    ->where('id-tipe', '=', $request->input('id-tipe'))
                    ->where('id-kategori', '=', $request->input('id-kategori'))
                    ->update(['saldo' => DB::raw('saldo + '.$selisih)]);
            }

            $akun = Akun::findOrFail($id);
            $akun->fill([
                'no-akun' => $request->input('no-akun'),
                'nama-akun' => $request->input('nama-akun'),
                'saldo' => $akun->saldo + $selisih,
            ]);
            $akun->save();
            DB::commit();
        }catch(QueryException $exception){
            DB::rollBack();
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
