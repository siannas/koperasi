<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Kategori;
use App\Saldo;

class NeracaController extends Controller
{
    private $filterDate = null;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->input('date_month')) {
            $this->filterDate = Carbon::createFromLocaleIsoFormat('!MMMM/Y', 'id', $request->input('date_month') . "/" . $this->year);
        } else {
            $this->filterDate = Carbon::createFromLocaleIsoFormat('!M/Y', 'id', date('n') . "/" . $this->year);
        }
        if($tipe=$request->get('tipe')){
            $data = $this->init($request, $tipe->id);
        }else{
            $data = $this->init($request);
        }
        $data['currentTipe'] = $tipe;
        return view('neraca', $data);
    }

    /**
     * Download excel neraca.
     *
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request, $year){
        $viewOnly = $request->isMethod('get');
        if ($request->input('date_month')) {
            $this->filterDate = Carbon::createFromLocaleIsoFormat('!MMMM/Y', 'id', $request->input('date_month') . "/" . $this->year);
        } else {
            $this->filterDate = Carbon::createFromLocaleIsoFormat('!M/Y', 'id', date('n') . "/" . $this->year);
        }
        if($tipe=$request->get('tipe')){
            $data = $this->init($request, $tipe->id);
        }else{
            $data = $this->init($request);
        }
        $data['currentTipe'] = $tipe;
        $tipeLiteral = $tipe ? $tipe->tipe : 'Gabungan';

        $tanggalString = $this->filterDate->translatedFormat('F_Y');
        
        $ex = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ex->getProperties()->setCreator("siannasGG");
        $ac = $ex->getActiveSheet();
        
        $ac->getStyle('C:E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $ac->getStyle('H:J')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        
        // KOP
        // $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        // $drawing->setPath(asset('img/logo.png'));
        // $drawing->setCoordinates('C1');
        // $drawing->setOffsetX(70);
        // $drawing->setOffsetY(5);
        // $drawing->setHeight(80);
        // $drawing->setWorksheet($ex->getActiveSheet());
        
        $ac->mergeCells('C1:J1');
        $ac->getCell('C1')->setValue("KOPERASI KONSUMEN PEGAWAI REPUBLIK INDONESIA");
        $ac->mergeCells('C2:J2');
        $ac->getCell('C2')->setValue("SEKRETARIAT DAERAH TINGKAT PROVINSI JAWA TIMUR");
        $ac->mergeCells('C3:J3');
        $ac->getCell('C3')->setValue("Jl. PAHLAWAN   No. 110   TELP. (031) 3524001-11  Ps. 1516, 1514, 1519 ");
        $ac->mergeCells('C4:J4');
        $ac->getCell('C4')->setValue("S U R A B A Y A");

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
        $ac->getStyle('B1:J1')->applyFromArray($titleStyle);
        $ac->getStyle('B2:J4')->applyFromArray($title2Style);

        // JUDUL
        $ac->mergeCells('B6:J6');
        $ac->getCell('B6')->setValue("NERACA PUSAT");
        $ac->getStyle('B6:J6')->getFont()->setSize(15)->setBold(true);
        $ac->getStyle('B6:J6')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        $ac->mergeCells('B7:J7');
        $ac->getCell('B7')->setValue("Periode {$data['month_literal']} {$data['year']}");
        $ac->getStyle('B7:J7')->getFont()->setSize(12)->setBold(true);
        $ac->getStyle('B7:J7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        // HEAD TABEL
        $ac->getCell('B10')->setValue("KETERANGAN");
        $ac->getCell('C10')->setValue("AWAL PERIODE");
        $ac->getCell('D10')->setValue("PERIODE BERJALAN");
        $ac->getCell('E10')->setValue("AKHIR PERIODE");
        $ac->getCell('G10')->setValue("KETERANGAN");
        $ac->getCell('H10')->setValue("AWAL PERIODE");
        $ac->getCell('I10')->setValue("PERIODE BERJALAN");
        $ac->getCell('J10')->setValue("AKHIR PERIODE");
        $ac->getColumnDimension('B')->setWidth(35);
        $ac->getColumnDimension('G')->setWidth(35);
        $ac->getColumnDimension('C')->setWidth(18);
        $ac->getColumnDimension('D')->setWidth(18);
        $ac->getColumnDimension('E')->setWidth(18);
        $ac->getColumnDimension('H')->setWidth(18);
        $ac->getColumnDimension('I')->setWidth(18);
        $ac->getColumnDimension('J')->setWidth(18);
        $ac->getColumnDimension('F')->setWidth(2); //pembatas
        $ac->getStyle('B10:J10')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
        // $ac->getStyle('B10:E10')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        // $ac->getStyle('G10:J10')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $ac->getStyle("B10:E10")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00B0F0');
        $ac->getStyle("G10:J10")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00B0F0');

        // Isi Aset
        $from=11;
        $walk=0;
        $total_saldo_berjalan=0;
        $total_saldo_awal=0;
        foreach($data['aset'] as $k => $kd){
            $saldo_berjalan=0;
            $saldo_awal=0;
            $now = $walk;
            foreach($kd['data'] as $akun){
                $walk++;
                $awal=$akun['saldo_awal'];
                $cur=$akun['saldo'];
                $saldo_awal+=$awal;
                $saldo_berjalan+=$cur;
                $ac->getCell('B'.($from+$walk))->setValue( "      ".$akun['name'] );
                $ac->getCell('C'.($from+$walk))->setValue( number_format($awal,2) );
                $ac->getCell('D'.($from+$walk))->setValue( number_format($cur,2) );
                $ac->getCell('E'.($from+$walk))->setValue( number_format($awal+$cur,2) );
                $ac->getStyle("B".($from+$walk).":E".($from+$walk))->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F2F2F2');
            }

            // display total saldo kategori
            $row = $from + $now;
            $ac->getCell('B'.($row))->setValue( $kd['name'] );
            $ac->getCell('C'.($row))->setValue( number_format($saldo_awal,2) );
            $ac->getCell('D'.($row))->setValue( number_format($saldo_berjalan,2) );
            $ac->getCell('E'.($row))->setValue( number_format($saldo_awal+$saldo_berjalan,2) );
            $ac->getStyle("B{$row}:E{$row}")->getFont()->setBold(true);
            // $ac->getStyle("B{$row}:E{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $ac->getStyle("B{$row}:E{$row}")->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('00B0F0');
            $walk++;
            $total_saldo_berjalan+=$saldo_berjalan;
            $total_saldo_awal+=$saldo_awal;
        }

        // set total aset
        $row = $from+$walk;
        $ASET_akhir_aset=$total_saldo_awal+$total_saldo_berjalan;  
        $ASET_total_saldo_berjalan=$total_saldo_berjalan;
        $ASET_total_saldo_awal=$total_saldo_awal;
        $AsetMaxWalk=$walk;

        // Isi KEWAJIBAN
        $from=11;
        $walk=0;
        $total_saldo_berjalan=0;
        $total_saldo_awal=0;
        foreach($data['beban'] as $k => $kd){
            $saldo_berjalan=0;
            $saldo_awal=0;
            $now = $walk;
            foreach($kd['data'] as $akun){
                $walk++;
                $awal=$akun['saldo_awal'];
                $cur=$akun['saldo'];
                $saldo_awal+=$awal;
                $saldo_berjalan+=$cur;
                $ac->getCell('G'.($from+$walk))->setValue("      ".$akun['name']);
                $ac->getCell('H'.($from+$walk))->setValue(number_format($awal, 2));
                $ac->getCell('I'.($from+$walk))->setValue(number_format($cur, 2));
                $ac->getCell('J'.($from+$walk))->setValue(number_format($awal+$cur, 2));
                $ac->getStyle("G".($from+$walk).":J".($from+$walk))->getFill()
                    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                    ->getStartColor()->setRGB('F2F2F2');
            }   

            // display total saldo kategori kewajiban
            $row = $from + $now;
            $ac->getCell('G'.($row))->setValue( $kd['name'] );
            $ac->getCell('H'.($row))->setValue( number_format($saldo_awal,2) );
            $ac->getCell('I'.($row))->setValue( number_format($saldo_berjalan,2) );
            $ac->getCell('J'.($row))->setValue( number_format($saldo_awal+$saldo_berjalan,2) );
            $ac->getStyle("G{$row}:J{$row}")->getFont()->setBold(true);
            // $ac->getStyle("G{$row}:J{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $ac->getStyle("G{$row}:J{$row}")->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('00B0F0');
            $walk++;

            $total_saldo_berjalan+=$saldo_berjalan;
            $total_saldo_awal+=$saldo_awal;
        }

        // set total ASET DAN Kewajiban dan menampilkan pada bagian paling bawah tabel
        // aset
        $row = $from+ max($AsetMaxWalk, $walk);
        $ac->getCell('B'.($row))->setValue( "Jumlah Aset" );
        $ac->getCell('C'.($row))->setValue( number_format($ASET_total_saldo_awal,2) );
        $ac->getCell('D'.($row))->setValue( number_format($ASET_total_saldo_berjalan,2) );
        $ac->getCell('E'.($row))->setValue( number_format($ASET_akhir_aset,2) );                      
        $ac->getStyle("B{$row}:E{$row}")->getFont()->setBold(true);
        // $ac->getStyle("B{$row}:E{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $ac->getStyle("B{$row}:E{$row}")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00B0F0');

        //kewajiban
        $akhir_aset=$total_saldo_awal+$total_saldo_berjalan;  
        $ac->getCell('G'.($row))->setValue( "Jumlah Kewajiban" );
        $ac->getCell('H'.($row))->setValue( number_format($total_saldo_awal,2) );
        $ac->getCell('I'.($row))->setValue( number_format($total_saldo_berjalan,2) );
        $ac->getCell('J'.($row))->setValue( number_format($akhir_aset,2) );                      
        $ac->getStyle("G{$row}:J{$row}")->getFont()->setBold(true);
        // $ac->getStyle("G{$row}:J{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        $ac->getStyle("G{$row}:J{$row}")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('00B0F0');

        //set border luar tabel
        // $ac->getStyle("B{$from}:E{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
        // $ac->getStyle("G{$from}:J{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        if($akhir_aset!==$ASET_akhir_aset){
            $ac->getCell('B5')->setValue( "SALDO TIDAK SEIMBANG" );
            $ac->getStyle('B5')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('FFFF00');
        }else{
            $ac->getCell('B5')->setValue( "SALDO SEIMBANG" );
            $ac->getStyle('B5')->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('80FF00');
        }
        $ac->getStyle('B5')->getFont()->setSize(14)->setBold(true);
        $ac->getStyle('B5')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

        if($viewOnly){
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Html($ex);
            // $header = $writer->generateHTMLHeader();
            echo $writer->generateStyles();
            echo "<style>.gridlines td {border: 0;}</style>\n</head>";
            echo $writer->generateSheetData();
            echo $writer->generateHTMLFooter();
        }else{
            // send file ke user
            $fileName="Neraca_{$tipeLiteral}_".$tanggalString.".xlsx";
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($ex, 'Xlsx');
            $writer->save('php://output');
            exit;
        }
    }

    /**
     * Init data-data.
     *
     * @return Object
     */
    private function init(Request $request, ?int $tipe = null){
        $saldos = \App\Saldo::select('id-akun', DB::Raw('SUM(`saldo`) as saldo'))
            ->where('tanggal', '=', $this->filterDate->format('Y-m-') . '01')
            ->where('id-tipe', '<>', 0)
            ->when($tipe != null, function($q) use($tipe) {
                $q->where('id-tipe', '=', $tipe);
            })
            ->groupBy('id-akun')
            ->get();
        $saldosBerjalan = \App\Saldo::select('id-akun', DB::Raw('SUM(`saldo`) as saldo'))
            ->where('tanggal', '<', $this->filterDate->format('Y-m-') . '01')
            ->where('tanggal', '>=', $this->filterDate->format('Y-') . '01-01')
            ->where('id-tipe', '<>', 0)
            ->when($tipe != null, function($q) use($tipe) {
                $q->where('id-tipe', '=', $tipe);
            })
            ->groupBy('id-akun')
            ->get();
        $awal = \App\Saldo::select('id-akun', DB::Raw('SUM(`saldo_awal`) as saldo'))
            ->where('tanggal', '=', $this->filterDate->format('Y-') . '01-01')
            ->where('id-tipe', '=', 0)
            ->groupBy('id-akun')
            ->get();
        $categories = Kategori::with(['getAkun' => function($q) {
                $q->where('status', '=', 1)
                ->orderBy('no-akun','ASC');
            }])
            ->where('parent', '<>', Kategori::SHU)
            ->whereNotNull('tipe-pendapatan')
            ->orderBy('priority','ASC')
            ->get();
        $awal = $awal ? $awal = array_combine(array_column($awal->toArray(), 'id-akun'), $awal->toArray()) : [];
        $saldos = $saldos ? $saldos = array_combine(array_column($saldos->toArray(), 'id-akun'), $saldos->toArray()) : [];
        $saldosBerjalan = $saldosBerjalan ?
            $saldosBerjalan = array_combine(array_column($saldosBerjalan->toArray(), 'id-akun'), $saldosBerjalan->toArray()) : [];
        $aset = [];
        $beban = [];
        foreach ($categories as $category) {
            $childs = [];
            foreach ($category->getAkun as $akun) {
                $sb = array_key_exists($akun->{'id'}, $saldosBerjalan) ? floatval($saldosBerjalan[$akun->{'id'}]['saldo']) : 0;
                $childs[] = [
                    'id' => $akun->id,
                    'name' => $akun->{'no-akun'} . ' - ' . $akun->{'nama-akun'},
                    'saldo_awal' => (array_key_exists($akun->{'id'}, $awal) ? floatval($awal[$akun->{'id'}]['saldo']) : 0) + $sb,
                    'saldo' => array_key_exists($akun->{'id'}, $saldos) ? floatval($saldos[$akun->{'id'}]['saldo']) : 0,
                ];
            }
            if (empty($childs)) {
                continue;
            }
            $cat = [
                'id' => $category->id,
                'name' => $category->kategori,
                'data' => $childs,
            ];
            if ($category['tipe-pendapatan']  == 'debit') {
                $aset[] = $cat;
            } else {
                $beban[] = $cat;
            }
        }
    
        $month = $this->filterDate->month;
        $year = $this->year;
        $monthLiteral = $this->filterDate->translatedFormat('F');
        return [
            'date'=> $month.'/'.$year,
            'month_literal'=> $monthLiteral,
            'year' => $year,
            'aset' => $aset,
            'beban' => $beban,
        ];
    }
}
