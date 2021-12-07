<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JurnalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dateAwal=$this->date['date']->format('Y-m').'-01';
        $tipe=$request->get('tipe');
        $akuns = \App\Akun::where('id-tipe',$tipe->id)
            ->select('id','nama-akun')
            ->get();
        $jurnals = \App\Jurnal::where('id-tipe',$tipe->id)
            ->select('*',DB::raw("STR_TO_DATE('{$dateAwal}', '%Y-%m-%d') > tanggal AS isOld"),
                DB::raw("DATE_FORMAT(tanggal,'%d/%m/%Y') AS tanggal2"))
            ->with('akunDebit')
            ->with('akunKredit')
            ->orderBy('tanggal','DESC')
            ->get();
        return view('jurnal', [
            'akuns'=>$akuns,
            'currentTipe'=>$tipe,
            'jurnals'=>$jurnals,
            'date'=>$this->date['date']->format('m/Y'),
        ]);
    }

    public function filter(Request $request){
        $dateAwal=$this->date['date']->format('Y-m').'-01';
        
        $backDate=Carbon::instance($this->date['date'])->subMonths(1);
        
        $my=Carbon::createFromFormat('m/Y', $request->date);
        $month = $my->month;
        $year = $my->year;
        
        $tipe=$request->get('tipe');
        
        $akuns = \App\Akun::where('id-tipe',$tipe->id)
            ->select('id','nama-akun')
            ->get();
        $jurnals = \App\Jurnal::where('id-tipe',$tipe->id)
            ->select('*',DB::raw("STR_TO_DATE('{$dateAwal}', '%Y-%m-%d') > tanggal AS isOld"),
                DB::raw("DATE_FORMAT(tanggal,'%d/%m/%Y') AS tanggal2"))
            ->with('akunDebit')
            ->with('akunKredit')
            ->orderBy('tanggal','DESC')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->get();

        return view('jurnal', [
            'akuns'=>$akuns,
            'currentTipe'=>$tipe,
            'jurnals'=>$jurnals,
            'date'=>$request->date,
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
        $tipe=$request->get('tipe');
        $data = $request->validate([
            "tanggal" => "required",
            "keterangan" => "required",
            "id-debit" => "required",
            "debit" => "required",
            "id-kredit" => "required",
            "kredit" => "required",
        ]);
        try {
            $jurnal = new \App\Jurnal($data);
            $jurnal->{'id-tipe'} = $tipe->id;
            $jurnal->tanggal = Carbon::createFromFormat('d/m/Y', $data['tanggal'])->format('Y-m-d');
            $jurnal->save();
        }catch (QueryException $exception) {
            $this->flashError($exception->getMessage());
            return back();
        }

        $this->flashSuccess('Data Jurnal Berhasil Ditambahkan');
        return back();
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
        $data = $request->validate([
            "tanggal" => "required",
            "keterangan" => "required",
            "id-debit" => "required",
            "debit" => "required",
            "id-kredit" => "required",
            "kredit" => "required",
        ]);
        try {
            $jurnal = \App\Jurnal::findOrFail($id);
            $jurnal->fill($data);
            $jurnal->tanggal = Carbon::createFromFormat('d/m/Y', $data['tanggal'])->format('Y-m-d');
            $jurnal->save();
        }catch (QueryException $exception) {
            $this->flashError($exception->getMessage());
            return back();
        }

        $this->flashSuccess('Data Jurnal Berhasil Diperbarui');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $jurnal = \App\Jurnal::findOrFail($id);
            $jurnal->delete();
        }catch (QueryException $exception) {
            $this->flashError($exception->getMessage());
            return back();
        }
        
        $this->flashSuccess('Data Jurnal Berhasil Dihapus');
        return back();
    }
}
