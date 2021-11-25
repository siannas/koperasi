<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class JurnalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $tipe=$request->get('tipe');
        $akuns = \App\Akun::where('id-tipe',$tipe->id)
            ->select('id','nama-akun')
            ->get();
        $jurnals = \App\Jurnal::where('id-tipe',$tipe->id)
            ->with('akunDebit')
            ->with('akunKredit')
            ->get();

        return view('jurnal', [
            'akuns'=>$akuns,
            'currentTipe'=>$tipe,
            'jurnals'=>$jurnals,
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
        $jurnal = new \App\Jurnal($data);
        $jurnal->{'id-tipe'} = $tipe->id;
        $jurnal->tanggal = Carbon::createFromFormat('d/m/Y', $data['tanggal'])->format('Y-m-d');
        $jurnal->save();
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
