<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Jurnal;
use App\Akun;
use App\Saldo;

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
                                'saldoAwal'=>new Saldo(),
                                'bulan'=>0]);
    }

    public function filter(Request $request){
        $tipe=$request->get('tipe');
        $akun=Akun::where('id-tipe', $tipe->id)->get();
        
        $filter = Carbon::createFromFormat('m/Y', $request->bulan);
        $month = $filter->month;
        $year = $filter->year;
        
        $saldoAwal=Saldo::where('id-akun', $request->akun)
            ->whereMonth('tanggal', $month-1)
            ->whereYear('tanggal', $year)->first();
        if(!$saldoAwal) $saldoAwal=new Saldo();

        $tipePen = Akun::with(['getKategori' => function($query) { 
                $query->select('id','tipe-pendapatan');
                }])->where('akun.id', $request->akun)->first();

        $jurnal = Jurnal::where('id-tipe', $tipe->id)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('id-debit', $request->akun)
            ->orWhere('id-kredit', $request->akun)
            ->get()->sortBy('tanggal');

        return view('bukuBesar', ['currentTipe'=>$tipe, 
                                'akun'=>$akun, 
                                'curAkun'=>$tipePen, 
                                'jurnal'=>$jurnal, 
                                'saldoAwal'=>$saldoAwal,
                                'bulan'=>$request->bulan]);
    }

    public function excel(Request $request){

        $tipe=$request->get('tipe');
        $akun=Akun::where('id-tipe', $tipe->id)->get();
        
        $filter = Carbon::createFromFormat('m/Y', $request->bulan);
        $month = $filter->month;
        $year = $filter->year;
        
        $saldoAwal=Saldo::where('id-akun', $request->akun)
            ->whereMonth('tanggal', $month-1)
            ->whereYear('tanggal', $year)->first();
        
        if(!$saldoAwal) $saldoAwal=new Saldo();

        $tipePen = Akun::with(['getKategori' => function($query) { 
                $query->select('id','tipe-pendapatan');
                }])->where('akun.id', $request->akun)->first();

        $jurnal = Jurnal::where('id-tipe', $tipe->id)
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('id-debit', $request->akun)
            ->orWhere('id-kredit', $request->akun)
            ->get()->sortBy('tanggal');
        
        $ex = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ex->getProperties()->setCreator("siannasGG");
        $ac = $ex->getActiveSheet();
        $ac->mergeCells('A1:F1');
        $ac->getCell('A1')->setValue("BUKU BESAR");
        $ac->getCell('A3')->setValue("NO. AKUN");
        $ac->getCell('A4')->setValue("NAMA AKUN");

        $ac->getCell('B3')->setValue(": ".$tipePen->{'no-akun'});
        $ac->getCell('B4')->setValue(": ".$tipePen->{'nama-akun'});

        $ac->getCell('E3')->setValue("SALDO AWAL");
        $ac->getCell('E4')->setValue("SALDO AKHIR");

        $ac->getCell('F3')->setValue(": Rp ".number_format($saldoAwal->saldo, 2));
        // $ac->getCell('F4')->setValue(": Rp 450,000.00");

        $ac->mergeCells('A6:A7');
        $ac->getCell('A6')->setValue("TANGGAL");
        $ac->mergeCells('B6:B7');
        $ac->getCell('B6')->setValue("NO. REF");
        $ac->mergeCells('C6:C7');
        $ac->getCell('C6')->setValue("URAIAN TRANSAKSI");
        $ac->mergeCells('D6:D7');
        $ac->getCell('D6')->setValue("DEBIT");
        $ac->mergeCells('E6:E7');
        $ac->getCell('E6')->setValue("KREDIT");
        $ac->mergeCells('F6:F7');
        $ac->getCell('F6')->setValue("SALDO");
        
        $ac->getCell('C8')->setValue('SALDO AWAL');
        $ac->getCell('F8')->setValue(number_format($saldoAwal->saldo, 2));

        $jumlah = $saldoAwal->saldo;
        for($x=0;$x<count($jurnal);$x++){
            $ac->getCell('A'.($x+9))->setValue($jurnal[$x]->tanggal);
            $ac->getCell('B'.($x+9))->setValue($jurnal[$x]->{'no-ref'});
            $ac->getCell('C'.($x+9))->setValue($jurnal[$x]->keterangan);
            if($jurnal[$x]->{'id-debit'}==$tipePen->id){
                $ac->getCell('D'.($x+9))->setValue(number_format($jurnal[$x]->debit,2));
                if($tipePen->getKategori->{'tipe-pendapatan'} == 'debit'){
                    $jumlah += intval($jurnal[$x]->debit);
                }
                elseif($tipePen->getKategori->{'tipe-pendapatan'} == 'kredit'){
                    $jumlah -= intval($jurnal[$x]->debit);
                }
            }
            else{
                $ac->getCell('D'.($x+9))->setValue('-');
            }
            if($jurnal[$x]->{'id-kredit'}==$tipePen->id){
                $ac->getCell('E'.($x+9))->setValue(number_format($jurnal[$x]->kredit,2));
                if($tipePen->getKategori->{'tipe-pendapatan'} == 'debit'){
                    $jumlah -= intval($jurnal[$x]->kredit);
                }
                elseif($tipePen->getKategori->{'tipe-pendapatan'} == 'kredit'){
                    $jumlah += intval($jurnal[$x]->kredit);
                }
            }
            else{
                $ac->getCell('E'.($x+9))->setValue('-');
            }
            
            $ac->getCell('F'.($x+9))->setValue(number_format($jumlah,2));
        }
        $ac->getCell('F4')->setValue(": Rp ".(number_format($jumlah,2)));
        
        
        $titleStyle = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'font' => [
                'bold' => true,
                'size' => 15,
            ],
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
        $ac->getStyle('A1')->applyFromArray($titleStyle);
        $ac->getStyle('A6:'.$col.(count($jurnal)+8))->applyFromArray($headerStyle);
        
        $fileName="BukuBesar_".$tipePen->{'nama-akun'}.".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($ex, 'Xlsx');
        $writer->save('php://output');
        exit;
        
    }
}
