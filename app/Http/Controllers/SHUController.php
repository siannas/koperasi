<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;

class SHUController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $data = $this->init($request);
        return view('shu', $data);
    }

    /**
     * Init data-data.
     *
     * @return Object
     */
    private function init(Request $request){
        //dapetin tanggal shu
        $month = $this->date['m'];
        $year = $this->date['y'];
        $backDate=Carbon::instance($this->date['date'])->subMonths(1);
        if ($request->input('date')) {
            $my=Carbon::createFromFormat('m/Y', $request->input('date'));
            $month = $my->month;
            $year = $my->year;
            $backDate=$my->subMonths(1);
        }
        $tipe=$request->get('tipe');
        
        $saldos=\App\Saldo::whereMonth('tanggal', $backDate->month)
            ->whereYear('tanggal', $backDate->year)
            ->select('id', 'id-kategori', 'id-akun', 'saldo')
            ->get()->keyBy('id-akun');

        // Ambil kategori parent yang SHU
        $SHU=\App\Kategori::where('kategori', 'SHU')->select('id')->first();

        $kategoris=\App\Kategori::with(['getAkun' => function($q) use($tipe) {
            $q->select('id','nama-akun','no-akun', 'id-kategori','id-tipe')
                ->where('id-tipe',$tipe->id);
            }])
            ->where('parent',$SHU->id)
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

        $meta=\App\Meta::where('key','like','shu_%')->get();

        return [
            'meta'=>$meta,
            'currentTipe'=>$tipe,
            'date'=> $month.'/'.$year,
            'kategoris' => $kategoris,
            'jurnal_debit' => $jurnal_debit,
            'jurnal_kredit' => $jurnal_kredit,
            'saldos'=>$saldos,
        ];
    }

    /**
     * fungsi untuk kalkulasi rumus pada meta data shu.
     * 
     * @param  Object  $master
     * @param  String  $formula
     * 
     * @return Array   [awal, berjalan, akhir]
     */
    public static function calculate(&$master, $formula)
    {
        $awal=[];
        $berjalan=[];
        $res=[];
        foreach ($master as $id=>$m) {
            $awal['/\['.$id.']/']=strval($m['awal']);
            $berjalan['/\['.$id.']/']=strval($m['berjalan']);
        }
        $awal_res = preg_replace(array_keys($awal), array_values($awal), $formula);
        $awal_res=eval('return '.$awal_res.';');
        $berjalan_res = preg_replace(array_keys($berjalan), array_values($berjalan), $formula);
        $berjalan_res=eval('return '.$berjalan_res.';');
        $akhir=$awal_res+$berjalan_res;
        return([$awal_res, $berjalan_res, $akhir]);
    }
}
