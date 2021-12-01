<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jurnal;
use App\Akun;
use App\Saldo;

class BukuBesarController extends Controller
{
    public function index(Request $request){
        $tipe=$request->get('tipe');
        $akun=Akun::where('id-tipe', $tipe->id)->get();
        $jurnal=[];
        
        return view('bukuBesar', ['currentTipe'=>$tipe, 
                                'akun'=>$akun, 
                                'curAkun'=>new Akun(),
                                'jurnal'=>$jurnal,
                                'saldoAwal'=>new Saldo(),
                                'bulan'=>0]);
    }

    public function filter(Request $request){
        $tipe=$request->get('tipe');
        $akun=Akun::where('id-tipe', $tipe->id)->get();
        
        $filter = Carbon::createFromFormat('m/Y', $request->bulan);
        $month = $filter->month;
        $year = $filter->year;
        
        $saldoAwal=Saldo::where('id-akun', $request->akun)
            ->whereMonth('tanggal', $month-1)
            ->whereYear('tanggal', $year)->first();
        if(!$saldoAwal) $saldoAwal=new Saldo();

        $tipePen = Akun::with(['getKategori' => function($query) { 
                $query->select('id','tipe-pendapatan');
                }])->where('akun.id', $request->akun)->first();

        $jurnal = Jurnal::where('id-tipe', $tipe->id)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('id-debit', $request->akun)
            ->orWhere('id-kredit', $request->akun)
            ->get()->sortBy('tanggal');

        return view('bukuBesar', ['currentTipe'=>$tipe, 
                                'akun'=>$akun, 
                                'curAkun'=>$tipePen, 
                                'jurnal'=>$jurnal, 
                                'saldoAwal'=>$saldoAwal,
                                'bulan'=>$request->bulan]);
    }
}
