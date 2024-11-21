<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Tipe;
use App\Kategori;
use App\Akun;
use App\Saldo;
use Validator;

class AkunController extends Controller
{
    public function index(){
        $kategori = Kategori::whereNotNull('parent')->get();

        $akun = Akun::with('getTipe', 'getKategori')
            ->select('akun.*', 'saldo.saldo_awal')
            ->leftJoin('saldo', function($join){
                #Tipe Gabungan
                $join->on('saldo.id-tipe', 'LIKE', DB::raw(0));
                $join->on('akun.id', '=', 'saldo.id-akun');
                $join->where('saldo.tanggal', '=', DB::raw("'{$this->year}-01-01'"));
            })
            ->groupBy('akun.id');
        $akun = $akun->get();
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

    public function update(Request $request, $year, $id){
        $validator = Validator::make($request->all(), [
            "no-akun" => "required|string",
            "nama-akun" => "required|string",
            "saldo_awal" => "numeric",
            "saldo" => "numeric",
        ]);
        if ($validator->fails()) return back()->with('error', 'Request Error');
        $input = $validator->valid();
        $input["saldo_awal"] = $input["saldo_awal"] ? floatval($input["saldo_awal"]) : null;
        $input["saldo"] = $input["saldo"] ? floatval($input["saldo"]) : null;
        
        DB::beginTransaction();
        try{
            $akun = Akun::findOrFail($id);
            $fill = [
                'no-akun' => $input['no-akun'],
                'nama-akun' => $input['nama-akun'],
            ];
            if ($input['saldo']) {
                $fill['saldo'] = $input['saldo'];
            }
            $akun->fill($fill);
            $akun->save();

            if ($input['saldo_awal']) {
                $saldoAwal = Saldo::upsert([[
                        #Tipe Gabungan
                        'id-tipe' => 0,
                        'id-akun' => $id,
                        'id-kategori' => $akun->{'id-kategori'},
                        'saldo_awal' => $input['saldo_awal'],
                        'tanggal' => $this->year . '-01-01',
                    ]], 
                    ['id-tipe', 'id-akun', 'id-kategori', 'tanggal',],
                    ['saldo_awal']
                );
            }
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
