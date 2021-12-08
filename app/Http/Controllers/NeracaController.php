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
        $data = $this->init($request);
        return view('neraca', $data);
    }

    /**
     * Download excel neraca.
     *
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request){
        $d = $this->init($request);

        $tanggalString = Carbon::createFromFormat('m/Y', $d['date'])->isoFormat('MMMM Y');
        
        $ex = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ex->getProperties()->setCreator("siannasGG");
        $ac = $ex->getActiveSheet();

        $ac->getStyle('C:E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $ac->getStyle('H:J')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // JUDUL
        $ac->getCell('B2')->setValue("KPRI. SETDAPROV. JATIM");
        $ac->getCell('B3')->setValue("NERACA PUSAT");
        $ac->getCell('B4')->setValue("Periode ".$tanggalString);
        $ac->mergeCells('B2:J2');
        $ac->getStyle('B2:J2')->getFont()->setSize(16)->setBold(true);
        $ac->getStyle('B2:J2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $ac->mergeCells('B3:J3');
        $ac->getStyle('B3:J3')->getFont()->setSize(14)->setBold(true);
        $ac->getStyle('B3:J3')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);;
        $ac->mergeCells('B4:J4');
        $ac->getStyle('B4:J4')->getFont()->setSize(14)->setBold(true);
        $ac->getStyle('B4:J4')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);;
        

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

        // Isi Aset
        $from=7;
        $walk=0;
        $total_saldo_berjalan=0;
        $total_saldo_awal=0;
        foreach($d['kategoris_debit'] as $k => $kd){
            if($kd->getAkun->isEmpty() === false and intval($kd->getAkun[0]->{'id-tipe'})===$d['currentTipe']->id){
                $saldo_berjalan=0;
                $saldo_awal=0;

                // display per kategori
                $now=$walk;
                foreach($kd->getAkun as $akun){
                    $walk++;

                    $debit=$d['jurnal_debit']->has($akun->id) ? $d['jurnal_debit'][$akun->id]->debit : 0;
                    $kredit=$d['jurnal_kredit']->has($akun->id) ? $d['jurnal_kredit'][$akun->id]->kredit : 0;
                    $cur=$debit-$kredit;
                    $awal=$d['saldos']->has($akun->id) ? $d['saldos'][$akun->id]->saldo : 0;
                    $saldo_awal+=$awal;
                    $saldo_berjalan+=$cur;

                    $ac->getCell('B'.($from+$walk))->setValue( "      ".$akun->{'nama-akun'} );
                    $ac->getCell('C'.($from+$walk))->setValue( number_format($awal,2) );
                    $ac->getCell('D'.($from+$walk))->setValue( number_format($cur,2) );
                    $ac->getCell('E'.($from+$walk))->setValue( number_format($awal+$cur,2) );   
                }

                // display total saldo kategori
                $row = $from+$now;
                $ac->getCell('B'.($row))->setValue( $kd->kategori );
                $ac->getCell('C'.($row))->setValue( number_format($saldo_awal,2) );
                $ac->getCell('D'.($row))->setValue( number_format($saldo_berjalan,2) );
                $ac->getCell('E'.($row))->setValue( number_format($saldo_awal+$saldo_berjalan,2) );
                $ac->getStyle("B{$row}:E{$row}")->getFont()->setBold(true);
                $ac->getStyle("B{$row}:E{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $walk++;

                $total_saldo_berjalan+=$saldo_berjalan;
                $total_saldo_awal+=$saldo_awal;
            }
        }

        // set total aset
        $row = $from+$walk;
        $ASET_akhir_aset=$total_saldo_awal+$total_saldo_berjalan;  
        $ASET_total_saldo_berjalan=$total_saldo_berjalan;
        $ASET_total_saldo_awal=$total_saldo_awal;
        $AsetMaxWalk=$walk;

        // Isi KEWAJIBAN
        $from=7;
        $walk=0;
        $total_saldo_berjalan=0;
        $total_saldo_awal=0;
        foreach($d['kategoris_kredit'] as $k => $kd){
            if($kd->getAkun->isEmpty() === false and intval($kd->getAkun[0]->{'id-tipe'})===$d['currentTipe']->id){
                $saldo_berjalan=0;
                $saldo_awal=0;

                // display per kategori kewajiban
                $now=$walk;
                foreach($kd->getAkun as $akun){
                    $walk++;

                    $debit=$d['jurnal_debit']->has($akun->id) ? $d['jurnal_debit'][$akun->id]->debit : 0;
                    $kredit=$d['jurnal_kredit']->has($akun->id) ? $d['jurnal_kredit'][$akun->id]->kredit : 0;
                    $cur=$kredit-$debit;
                    $awal=$d['saldos']->has($akun->id) ? $d['saldos'][$akun->id]->saldo : 0;
                    $saldo_awal+=$awal;
                    $saldo_berjalan+=$cur;

                    $ac->getCell('G'.($from+$walk))->setValue( "      ".$akun->{'nama-akun'} );
                    $ac->getCell('H'.($from+$walk))->setValue( number_format($awal,2) );
                    $ac->getCell('I'.($from+$walk))->setValue( number_format($cur,2) );
                    $ac->getCell('J'.($from+$walk))->setValue( number_format($awal+$cur,2) );   
                }

                // display total saldo kategori kewajiban
                $row = $from+$now;
                $ac->getCell('G'.($row))->setValue( $kd->kategori );
                $ac->getCell('H'.($row))->setValue( number_format($saldo_awal,2) );
                $ac->getCell('I'.($row))->setValue( number_format($saldo_berjalan,2) );
                $ac->getCell('J'.($row))->setValue( number_format($saldo_awal+$saldo_berjalan,2) );
                $ac->getStyle("G{$row}:J{$row}")->getFont()->setBold(true);
                $ac->getStyle("G{$row}:J{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $walk++;

                $total_saldo_berjalan+=$saldo_berjalan;
                $total_saldo_awal+=$saldo_awal;
            }
        }

        // set total ASET DAN Kewajiban dan menampilkan pada bagian paling bawah tabel
        // aset
        $row = $from+ max($AsetMaxWalk, $walk);
        $ac->getCell('B'.($row))->setValue( "Jumlah Aset" );
        $ac->getCell('C'.($row))->setValue( number_format($ASET_total_saldo_awal,2) );
        $ac->getCell('D'.($row))->setValue( number_format($ASET_total_saldo_berjalan,2) );
        $ac->getCell('E'.($row))->setValue( number_format($ASET_akhir_aset,2) );                      
        $ac->getStyle("B{$row}:E{$row}")->getFont()->setBold(true);
        $ac->getStyle("B{$row}:E{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        //kewajiban
        $akhir_aset=$total_saldo_awal+$total_saldo_berjalan;  
        $ac->getCell('G'.($row))->setValue( "Jumlah Kewajiban" );
        $ac->getCell('H'.($row))->setValue( number_format($total_saldo_awal,2) );
        $ac->getCell('I'.($row))->setValue( number_format($total_saldo_berjalan,2) );
        $ac->getCell('J'.($row))->setValue( number_format($akhir_aset,2) );                      
        $ac->getStyle("G{$row}:J{$row}")->getFont()->setBold(true);
        $ac->getStyle("G{$row}:J{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        //set border luar tabel
        $ac->getStyle("B{$from}:E{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $ac->getStyle("G{$from}:J{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        if($akhir_aset!==$ASET_akhir_aset){
            $ac->getCell('B1')->setValue( "SALDO TIDAK SEIMBANG" );
            $ac->getStyle('B1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('FFFF00');
        }else{
            $ac->getCell('B1')->setValue( "SALDO SEIMBANG" );
            $ac->getStyle('B1')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('80FF00');
        }
        $ac->getStyle('B1')->getFont()->setSize(14)->setBold(true);
        $ac->getStyle('B1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // send file ke user
        $fileName="Neraca_".$tanggalString.".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($ex, 'Xlsx');
        $writer->save('php://output');
        exit;
    }

    /**
     * Init data-data.
     *
     * @return Object
     */
    private function init(Request $request){
        //dapetin tanggal neraca yg ingin di download
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
    
        return [
            'currentTipe'=>$tipe,
            'date'=> $month.'/'.$year,
            'kategoris_debit' => $kategoris_debit,
            'kategoris_kredit' => $kategoris_kredit,
            'jurnal_debit' => $jurnal_debit,
            'jurnal_kredit' => $jurnal_kredit,
            'saldos'=>$saldos,
        ];
    }
}
