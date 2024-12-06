<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use App\Kategori;
use App\Saldo;

class SHUController extends Controller
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
            $data = $this->init($request, $tipe->id, $tipe->slug);
        }else{
            $data = $this->init($request);
        }
        $data['currentTipe'] = $tipe;
        return view('shu', $data);
    }

    /**
     * Download excel shu
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
            $data = $this->init($request, $tipe->id, $tipe->slug);
        }else{
            $data = $this->init($request);
        }
        $data['currentTipe'] = $tipe;

        $tanggalString = "{$data['month_literal']} {$year}";
        
        $ex = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ex->getProperties()->setCreator("siannasGG");
        $ac = $ex->getActiveSheet();

        $ac->getStyle('C:G')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

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
        foreach($data['shu'] as $k => $kd){
            $master[$kd['id']]=[
                'awal'=>0,
                'berjalan'=>0,
            ];
            $saldo_berjalan=0;
            $saldo_awal=0;
            // display per kategori
            $now=$walk;
            foreach($kd['data'] as $akun){
                // pastikan nama akun belum di-visit (guna view gabungan untuk nama akun yg sama)
                $walk++;
                $saldo_awal+=$akun['saldo_awal'];
                $saldo_berjalan+=$akun['saldo'];

                $ac->getCell('B'.($from+$walk))->setValue("      ".$akun['name']);
                $ac->getCell('C'.($from+$walk))->setValue(indo_num_format($akun['saldo_awal'], 2));
                $ac->getCell('D'.($from+$walk))->setValue(indo_num_format($akun['saldo'], 2));
                $ac->getCell('E'.($from+$walk))->setValue(indo_num_format($akun['saldo_awal'] + $akun['saldo'], 2));
            }

            // display total saldo kategori
            $row = $from+$now;
            $ac->getCell('B'.($row))->setValue( $kd['name'] );
            $walk++;
            $row = $from+$walk;
            $ac->getCell('B'.($row))->setValue( 'JUMLAH '.strtoupper($kd['name']) );
            $ac->getCell('C'.($row))->setValue( indo_num_format($saldo_awal,2) );
            $ac->getCell('D'.($row))->setValue( indo_num_format($saldo_berjalan,2) );
            $ac->getCell('E'.($row))->setValue( indo_num_format($saldo_awal+$saldo_berjalan,2) );
            $ac->getStyle("B{$row}:G{$row}")->getFont()->setBold(true);
            $ac->getStyle("B{$row}:G{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $walk++;

            $master[$kd['id']]=[
                'awal'=>$saldo_awal,
                'berjalan'=>$saldo_berjalan,
            ];
        }

        // kalkulasi semua rumus total dari tabel meta shu
        foreach($data['meta'] as $i=>$m){
            $title=ucwords(str_replace("_"," ", substr($m->key, $data['metaKeyLen']) ));
            $res=$this->calculate($master, $m->value);

            $row = $from+$walk;
            $ac->getCell('B'.($row))->setValue( $title );
            $ac->getCell('C'.($row))->setValue( indo_num_format($res[0],2) );
            $ac->getCell('D'.($row))->setValue( indo_num_format($res[1],2) );
            $ac->getCell('E'.($row))->setValue( indo_num_format($res[2],2) );                      
            $ac->getStyle("B{$row}:G{$row}")->getFont()->setBold(true);
            $ac->getStyle("B{$row}:G{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
            $walk++;
        }

        //set border luar tabel
        $ac->getStyle("B{$from}:G{$row}")->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

        // KOP
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();

        $drawing->setPath(base_path('public/img/logo.png'));
        $drawing->setCoordinates('B1');
        $drawing->setOffsetX(100);
        $drawing->setOffsetY(5);
        $drawing->setHeight(80);
        $drawing->setWorksheet($ex->getActiveSheet());

        if($viewOnly){
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Html($ex);
            // $header = $writer->generateHTMLHeader();
            echo $writer->generateStyles();
            echo "<style>.gridlines td {border: 0;}</style>\n</head>";
            echo $writer->generateSheetData();
            echo $writer->generateHTMLFooter();
        }else{
            // send file ke user
            $fileName="SHU_{$data['month_literal']}_{$year}.xlsx";
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
    private function init(Request $request, ?int $tipe = null, string $slug = ''){
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
            ->where('parent', '=', Kategori::SHU)
            ->orderBy('priority','ASC')
            ->get();
        $awal = $awal ? $awal = array_combine(array_column($awal->toArray(), 'id-akun'), $awal->toArray()) : [];
        $saldos = $saldos ? $saldos = array_combine(array_column($saldos->toArray(), 'id-akun'), $saldos->toArray()) : [];
        $saldosBerjalan = $saldosBerjalan ?
            $saldosBerjalan = array_combine(array_column($saldosBerjalan->toArray(), 'id-akun'), $saldosBerjalan->toArray()) : [];
        $shu = [];
        foreach ($categories as $category) {
            $childs = [];
            $saldoAwal = 0;
            foreach ($category->getAkun as $akun) {
                $sb = array_key_exists($akun->{'id'}, $saldosBerjalan) ? floatval($saldosBerjalan[$akun->{'id'}]['saldo']) : 0;
                $childs[] = [
                    'id' => $akun->id,
                    'name' => $akun->{'no-akun'} . ' - ' . $akun->{'nama-akun'},
                    'saldo_awal' => (array_key_exists($akun->{'id'}, $awal) ? floatval($awal[$akun->{'id'}]['saldo']) : 0) + $sb * -1,
                    'saldo' => array_key_exists($akun->{'id'}, $saldos) ? -1 * floatval($saldos[$akun->{'id'}]['saldo']) : 0,
                ];
                $saldoAwal += $childs[count($childs) - 1]['saldo_awal'];
            }
            $cat = [
                'id' => $category->id,
                'name' => $category->kategori,
                'data' => $childs,
            ];
            $shu[] = $cat;
        }
    
        $month = $this->filterDate->month;
        $year = $this->year;
        $monthLiteral = $this->filterDate->translatedFormat('F');

        $meta = [];
        $metaForeKey="shu_".strtolower($slug).'_';
        $meta=\App\Meta::where('key','like',$metaForeKey.'%')
            ->when($slug =='', function($q){
                $q->where('key','not like','shu_tk%')
                ->where('key','not like','shu_fc%')
                ->where('key','not like','shu_usp%');
            })->get();
        return [
            'metaKeyLen' => max(strlen($metaForeKey)-1, 0),
            'meta'=>$meta,
            'date'=> $month.'/'.$year,
            'shu' => $shu,
            'month_literal'=> $monthLiteral,
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
            $awal['/\['.$id.']/']="(".strval($m['awal']).")";
            $berjalan['/\['.$id.']/']="(".strval($m['berjalan']).")";
        }
        $awal_res = preg_replace(array_keys($awal), array_values($awal), $formula);
        $awal_res=eval('return '.$awal_res.';');
        $berjalan_res = preg_replace(array_keys($berjalan), array_values($berjalan), $formula);
        $berjalan_res=eval('return '.$berjalan_res.';');
        $akhir=$awal_res+$berjalan_res;
        return([$awal_res, $berjalan_res, $akhir]);
    }
}
