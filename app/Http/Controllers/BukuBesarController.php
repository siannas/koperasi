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

        $ac->getCell('B3')->setValue(": ".$request->akun);
        $ac->getCell('B4')->setValue(": Kas");

        $ac->getCell('E3')->setValue("SALDO AWAL");
        $ac->getCell('E4')->setValue("SALDO AKHIR");

        $ac->getCell('F3')->setValue(": Rp 1,000,000.00");
        $ac->getCell('F4')->setValue(": Rp 450,000.00");

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
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
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
        $ac->getStyle('A6:'.$col.'10')->applyFromArray($headerStyle);
        
        $fileName="tes.xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($ex, 'Xlsx');
        $writer->save('php://output');
        exit;
        
    }
}
