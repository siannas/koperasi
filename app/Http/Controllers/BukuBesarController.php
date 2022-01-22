<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jurnal;
use App\Akun;
use App\Saldo;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
                                'saldoAwal'=>0,
                                'bulan'=>0]);
    }

    public function filter(Request $request){
        $tipe=$request->get('tipe');
        $akun=Akun::where('id-tipe', $tipe->id)->get();
        
        $filter = Carbon::createFromFormat('m/Y', $request->bulan);
        $month = $filter->month;
        $year = $filter->year;
        $filter->day = 1;
        
        $tipePen = Akun::with(['getKategori' => function($query) { 
                $query->select('id','tipe-pendapatan');
                }])->where('akun.id', $request->akun)->first();
        
        /** NOTE:
         * ambil akun dengan string nama yang sama
         * */ 
        $related=Akun::where('nama-akun','like',$tipePen->{'nama-akun'} )->select('id')->pluck('id')->toArray();
        
        $saldoAwal=Saldo::whereIn('id-akun', $related)
            ->whereDate('tanggal','<',$filter->format('Y-m-d'))
            ->pluck('saldo')->sum();
        if(!$saldoAwal) $saldoAwal=0;

        $jurnal = Jurnal::where('validasi', 1)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where(function($q) use($request,$related){
                $q->whereIn('id-debit', $related)
                  ->orWhereIn('id-kredit', $related);
            })
            ->get()->sortBy('tanggal');

        return view('bukuBesar', ['currentTipe'=>$tipe, 
                                'akun'=>$akun, 
                                'curAkun'=>$tipePen, 
                                'jurnal'=>$jurnal, 
                                'saldoAwal'=>$saldoAwal,
                                'bulan'=>$request->bulan,
                                'related'=>$related]);
    }

    public function excel(Request $request){

        $tipe=$request->get('tipe');
        $akun=Akun::where('id-tipe', $tipe->id)->get();
        
        $filter = Carbon::createFromFormat('m/Y', $request->bulan);
        $month = $filter->month;
        $year = $filter->year;
        $filter->day = 1;
        
        $tipePen = Akun::with(['getKategori' => function($query) { 
            $query->select('id','tipe-pendapatan');
            }])->where('akun.id', $request->akun)->first();
    
        /** NOTE:
         * ambil akun dengan string nama yang sama
         * */ 
        $related=Akun::where('nama-akun','like',$tipePen->{'nama-akun'} )->select('id')->pluck('id')->toArray();
        
        $saldoAwal=Saldo::whereIn('id-akun', $related)
            ->whereDate('tanggal','<',$filter->format('Y-m-d'))
            ->pluck('saldo')->sum();
        if(!$saldoAwal) $saldoAwal=0;

        $jurnal = Jurnal::where('validasi', 1)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where(function($q) use($request,$related){
                $q->whereIn('id-debit', $related)
                  ->orWhereIn('id-kredit', $related);
            })
            ->get()->sortBy('tanggal');
        
        $ex = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ex->getProperties()->setCreator("siannasGG");
        $ac = $ex->getActiveSheet();

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath('public/img/logo.png');
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(70);
        $drawing->setOffsetY(5);
        $drawing->setHeight(80);
        $drawing->setWorksheet($ex->getActiveSheet());

        $ac->mergeCells('C1:F1');
        $ac->getCell('C1')->setValue("KOPERASI KONSUMEN PEGAWAI REPUBLIK INDONESIA");
        $ac->mergeCells('C2:F2');
        $ac->getCell('C2')->setValue("SEKRETARIAT DAERAH TINGKAT PROVINSI JAWA TIMUR");
        $ac->mergeCells('C3:F3');
        $ac->getCell('C3')->setValue("Jl. PAHLAWAN   No. 110   TELP. (031) 3524001-11  Ps. 1516, 1514, 1519 ");
        $ac->mergeCells('C4:F4');
        $ac->getCell('C4')->setValue("S U R A B A Y A");

        $ac->mergeCells('A6:F6');
        $ac->getCell('A6')->setValue("BUKU BESAR ".(strtoupper($tipe->tipe)));
        $ac->getCell('A8')->setValue("NO. AKUN");
        $ac->getCell('A9')->setValue("NAMA AKUN");

        $ac->getCell('B8')->setValue(": ".$tipePen->{'no-akun'});
        $ac->getCell('B9')->setValue(": ".$tipePen->{'nama-akun'});

        $ac->getCell('E8')->setValue("SALDO AWAL");
        $ac->getCell('E9')->setValue("SALDO AKHIR");

        $ac->getCell('F8')->setValue(": Rp ".number_format($saldoAwal, 2));

        $ac->mergeCells('A11:A12');
        $ac->getCell('A11')->setValue("TANGGAL");
        $ac->mergeCells('B11:B12');
        $ac->getCell('B11')->setValue("NO. REF");
        $ac->mergeCells('C11:C12');
        $ac->getCell('C11')->setValue("URAIAN TRANSAKSI");
        $ac->mergeCells('D11:D12');
        $ac->getCell('D11')->setValue("DEBIT");
        $ac->mergeCells('E11:E12');
        $ac->getCell('E11')->setValue("KREDIT");
        $ac->mergeCells('F11:F12');
        $ac->getCell('F11')->setValue("SALDO");
        
        $ac->getCell('C13')->setValue('SALDO AWAL');
        $ac->getCell('F13')->setValue(number_format($saldoAwal, 2));

        $jumlah = $saldoAwal;
        for($x=0;$x<count($jurnal);$x++){
            $ac->getCell('A'.($x+14))->setValue($jurnal[$x]->tanggal);
            $ac->getCell('B'.($x+14))->setValue($jurnal[$x]->{'no-ref'});
            $ac->getCell('C'.($x+14))->setValue($jurnal[$x]->keterangan);
            if(in_array($jurnal[$x]->{'id-debit'},$related)){
                $ac->getCell('D'.($x+14))->setValue(number_format($jurnal[$x]->debit,2));
                if($tipePen->getKategori->{'tipe-pendapatan'} == 'debit'){
                    $jumlah += intval($jurnal[$x]->debit);
                }
                elseif($tipePen->getKategori->{'tipe-pendapatan'} == 'kredit'){
                    $jumlah -= intval($jurnal[$x]->debit);
                }
            }
            else{
                $ac->getCell('D'.($x+14))->setValue('-');
            }
            if(in_array($jurnal[$x]->{'id-kredit'},$related)){
                $ac->getCell('E'.($x+14))->setValue(number_format($jurnal[$x]->kredit,2));
                if($tipePen->getKategori->{'tipe-pendapatan'} == 'debit'){
                    $jumlah -= intval($jurnal[$x]->kredit);
                }
                elseif($tipePen->getKategori->{'tipe-pendapatan'} == 'kredit'){
                    $jumlah += intval($jurnal[$x]->kredit);
                }
            }
            else{
                $ac->getCell('E'.($x+14))->setValue('-');
            }
            
            $ac->getCell('F'.($x+14))->setValue(number_format($jumlah,2));
        }
        $ac->getCell('F9')->setValue(": Rp ".(number_format($jumlah,2)));
        
        
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

        $headerStyle = [
            'alignment' => [
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
        ];

        
        $col = $ac->getCellByColumnAndRow(6, 1)->getColumn();

        $ac->getColumnDimension('A')->setWidth(15);
        $ac->getColumnDimension('D')->setWidth(20);
        $ac->getColumnDimension('E')->setWidth(20);
        $ac->getColumnDimension('F')->setWidth(20);
        $ac->getColumnDimension('C')->setWidth(40);
        $ac->getStyle('C1')->applyFromArray($titleStyle);
        $ac->getStyle('A6')->applyFromArray($titleStyle);
        $ac->getStyle('A2:F4')->applyFromArray($title2Style);
        $ac->getStyle('A11:'.$col.(count($jurnal)+13))->applyFromArray($headerStyle);
        
        $fileName="BukuBesar_".$tipePen->{'nama-akun'}."_".$request->bulan.".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($ex, 'Xlsx');
        $writer->save('php://output');
        exit;
        
    }
}
