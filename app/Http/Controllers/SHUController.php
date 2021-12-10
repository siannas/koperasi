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
     * Download excel shu
     *
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request){
        $d = $this->init($request);

        $tanggalString = Carbon::createFromFormat('m/Y', $d['date'])->isoFormat('MMMM Y');
        
        $ex = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ex->getProperties()->setCreator("siannasGG");
        $ac = $ex->getActiveSheet();

        $ac->getStyle('C:G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // JUDUL
        $ac->getCell('B2')->setValue("KPRI. SETDAPROV. JATIM");
        $ac->getCell('B3')->setValue("NERACA PUSAT");
        $ac->getCell('B4')->setValue("Periode ".$tanggalString);
        $ac->mergeCells('B2:J2');
        $ac->getStyle('B2:J2')->getFont()->setSize(16)->setBold(true);
        $ac->getStyle('B2:J2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $ac->mergeCells('B3:J3');
        $ac->getStyle('B3:J3')->getFont()->setSize(14)->setBold(true);
        $ac->getStyle('B3:J3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
    
        $ac->mergeCells('B4:J4');
        $ac->getStyle('B4:J4')->getFont()->setSize(14)->setBold(true);
        $ac->getStyle('B4:J4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        

        // HEAD TABEL
        $ac->getCell('B6')->setValue("KETERANGAN");
        $ac->getCell('C6')->setValue("AWAL PERIODE");
        $ac->getCell('D6')->setValue("PERIODE BERJALAN");
        $ac->getCell('E6')->setValue("AKHIR PERIODE");
        $ac->getCell('G6')->setValue("KETERANGAN");
        $ac->getCell('H6')->setValue("AWAL PERIODE");
        $ac->getCell('I6')->setValue("PERIODE BERJALAN");
        $ac->getCell('J6')->setValue("AKHIR PERIODE");
        $ac->getColumnDimension('B')->setWidth(35);
        $ac->getColumnDimension('G')->setWidth(35);
        $ac->getColumnDimension('C')->setWidth(18);
        $ac->getColumnDimension('D')->setWidth(18);
        $ac->getColumnDimension('E')->setWidth(18);
        $ac->getColumnDimension('H')->setWidth(18);
        $ac->getColumnDimension('I')->setWidth(18);
        $ac->getColumnDimension('J')->setWidth(18);
        $ac->getColumnDimension('F')->setWidth(2); //pembatas
        $ac->getStyle('B6:J6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $ac->getStyle('B6:E6')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $ac->getStyle('G6:J6')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
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

        $metaForeKey="shu_".strtolower($tipe->slug).'_';
        $meta=\App\Meta::where('key','like',$metaForeKey.'%')->get();
        
        return [
            'metaKeyLen' => strlen($metaForeKey),
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
