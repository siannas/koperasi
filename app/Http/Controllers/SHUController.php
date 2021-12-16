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

        // KOP
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath('public/img/logo.png');
        $drawing->setCoordinates('B1');
        $drawing->setOffsetX(100);
        $drawing->setOffsetY(5);
        $drawing->setHeight(80);
        $drawing->setWorksheet($ex->getActiveSheet());

        $ac->mergeCells('B1:G1');
        $ac->getCell('B1')->setValue("KOPERASI KONSUMEN PEGAWAI REPUBLIK INDONESIA");
        $ac->mergeCells('B2:G2');
        $ac->getCell('B2')->setValue("SEKRETARIAT DAERAH TINGKAT PROVINSI JAWA TIMUR");
        $ac->mergeCells('B3:G3');
        $ac->getCell('B3')->setValue("Jl. PAHLAWAN   No. 110   TELP. (031) 3524001-11  Ps. 1516, 1514, 1519 ");
        $ac->mergeCells('B4:G4');
        $ac->getCell('B4')->setValue("S U R A B A Y A");

        $titleStyle = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'font' => [
                'bold' => true,
                'size' => 15,
            ],
        ];
        $title2Style = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'font' => [
                'bold' => true,
                'size' => 12,
            ],
            'borders' => [
                'bottom' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                ]
            ]
        ];
        $ac->getStyle('B1:G1')->applyFromArray($titleStyle);
        $ac->getStyle('B2:G4')->applyFromArray($title2Style);

        // JUDUL
        $ac->getCell('B6')->setValue("SELISIH HASIL USAHA");
        $ac->mergeCells('B6:G6');
        $ac->getStyle('B6:G6')->getFont()->setSize(15)->setBold(true);
        $ac->getStyle('B6:G6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);;
        
        $ac->getCell('B7')->setValue("Periode ".$tanggalString);
        $ac->mergeCells('B7:G7');
        $ac->getStyle('B7:G7')->getFont()->setSize(12)->setBold(true);
        $ac->getStyle('B7:G7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);;

        // HEAD TABEL
        $ac->getCell('B9')->setValue("KETERANGAN");
        $ac->getCell('C9')->setValue("AWAL PERIODE");
        $ac->getCell('D9')->setValue("PERIODE BERJALAN");
        $ac->getCell('E9')->setValue("AKHIR PERIODE");
        $ac->getCell('F9')->setValue("KOREKSI");
        $ac->getCell('G9')->setValue("FISKAL");
        
        $ac->getColumnDimension('B')->setWidth(35);
        $ac->getColumnDimension('C')->setWidth(18);
        $ac->getColumnDimension('D')->setWidth(18);
        $ac->getColumnDimension('E')->setWidth(18);
        $ac->getColumnDimension('F')->setWidth(18);
        $ac->getColumnDimension('G')->setWidth(18);
        $ac->getStyle('B9:G9')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        $ac->getStyle('B9:G9')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // Isi Aset
        $from=10;
        $walk=0;
        $master=[];
        foreach($d['kategoris'] as $k => $kd){
            if($kd->getAkun->isEmpty() === false and intval($kd->getAkun[0]->{'id-tipe'})===$d['currentTipe']->id){
                $saldo_berjalan=0;
                $saldo_awal=0;
                $coef= ($kd->{'tipe-pendapatan'} ==='kredit') ? -1 : 1;

                // display per kategori
                $now=$walk;
                foreach($kd->getAkun as $akun){
                    $walk++;

                    $debit=$d['jurnal_debit']->has($akun->id) ? $d['jurnal_debit'][$akun->id]->debit : 0;
                    $kredit=$d['jurnal_kredit']->has($akun->id) ? $d['jurnal_kredit'][$akun->id]->kredit : 0;
                    $cur=$coef*($debit-$kredit);
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
                $walk++;
                $row = $from+$walk;
                $ac->getCell('B'.($row))->setValue( 'JUMLAH '.strtoupper($kd->kategori) );
                $ac->getCell('C'.($row))->setValue( number_format($saldo_awal,2) );
                $ac->getCell('D'.($row))->setValue( number_format($saldo_berjalan,2) );
                $ac->getCell('E'.($row))->setValue( number_format($saldo_awal+$saldo_berjalan,2) );
                $ac->getStyle("B{$row}:G{$row}")->getFont()->setBold(true);
                $ac->getStyle("B{$row}:G{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $walk++;

                $master[$kd->id]=[
                    'awal'=>$saldo_awal,
                    'berjalan'=>$saldo_berjalan,
                ];
            }
        }

        // kalkulasi semua rumus total dari tabel meta shu
        foreach($d['meta'] as $i=>$m){
            $title=ucwords(str_replace("_"," ", substr($m->key, $d['metaKeyLen']) ));
            $res=$this->calculate($master, $m->value);

            $row = $from+$walk;
            $ac->getCell('B'.($row))->setValue( $title );
            $ac->getCell('C'.($row))->setValue( number_format($res[0],2) );
            $ac->getCell('D'.($row))->setValue( number_format($res[1],2) );
            $ac->getCell('E'.($row))->setValue( number_format($res[2],2) );                      
            $ac->getStyle("B{$row}:G{$row}")->getFont()->setBold(true);
            $ac->getStyle("B{$row}:G{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $walk++;
        }

        //set border luar tabel
        $ac->getStyle("B{$from}:G{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // send file ke user
        $fileName="SHU_".$tanggalString.".xlsx";
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
            ->where('validasi',1)
            ->get()->keyBy('id-debit');

        $jurnal_kredit=\App\Jurnal::where('id-tipe',$tipe->id)
            ->selectRaw('`id-kredit`, sum(kredit) as kredit')
            ->groupBy('id-kredit')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('validasi',1)
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
