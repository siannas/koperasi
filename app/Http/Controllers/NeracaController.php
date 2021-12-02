<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class NeracaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $month = $this->date['m'];
        $year = $this->date['y'];
        $backDate=Carbon::instance($this->date['date'])->subMonths(1);
        if($request->input('date')){
            $my=Carbon::createFromFormat('m/Y', $request->input('date'));
            $month = $my->month;
            $year = $my->year;
            $backDate=$my->subMonths(1);
        }
        $tipe=$request->get('tipe');
        
        $saldos=\App\Saldo::whereMonth('tanggal', $backDate->month)
            ->whereYear('tanggal', $backDate->year)
            ->select('id','id-kategori','id-akun','saldo')
            ->get()->keyBy('id-akun');

        // Ambil kategori parent yang non SHU
        $nonSHU=\App\Kategori::where('kategori','NON-SHU')->select('id')->first();
        
        $kategoris_debit=\App\Kategori::with(['getAkun' => function($q) use($tipe) {
                $q->select('id','nama-akun','no-akun', 'id-kategori','id-tipe')
                    ->where('id-tipe',$tipe->id);
            }])
            ->where('tipe-pendapatan', 'debit')
            ->where('parent',$nonSHU->id)
            ->get();

        $kategoris_kredit=\App\Kategori::with(['getAkun' => function($q) use($tipe) {
                $q->select('id','nama-akun','no-akun', 'id-kategori','id-tipe')
                    ->where('id-tipe',$tipe->id);
            }])
            ->where('tipe-pendapatan', 'kredit')
            ->where('parent',$nonSHU->id)
            ->get();

        $jurnal_debit=\App\Jurnal::where('id-tipe',$tipe->id)
            ->selectRaw('`id-debit`, sum(debit) as debit')
            ->groupBy('id-debit')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->get()->keyBy('id-debit');

        $jurnal_kredit=\App\Jurnal::where('id-tipe',$tipe->id)
            ->selectRaw('`id-kredit`, sum(kredit) as kredit')
            ->groupBy('id-kredit')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->get()->keyBy('id-kredit');
    
        return view('neraca', [
            'currentTipe'=>$tipe,
            'date'=> $month.'/'.$year,
            'kategoris_debit' => $kategoris_debit,
            'kategoris_kredit' => $kategoris_kredit,
            'jurnal_debit' => $jurnal_debit,
            'jurnal_kredit' => $jurnal_kredit,
            'saldos'=>$saldos,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
