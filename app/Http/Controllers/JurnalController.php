<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JurnalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $dateAwal=$this->date['date']->format('Y-m').'-01';
        $tipe=$request->get('tipe');
        $my=Carbon::createFromFormat('m/Y', $this->date['date']->format('m/Y'));
        $month = $my->month;
        $year = $my->year;

        $akuns = \App\Akun::where('id-tipe',$tipe->id)
            ->select('id','nama-akun')
            ->get();
        $jurnals = \App\Jurnal::where('id-tipe',$tipe->id)
            ->with('akunDebit')
            ->with('akunKredit')
            ->orderBy('tanggal','DESC')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->get();
        return view('jurnal', [
            'akuns'=>$akuns,
            'currentTipe'=>$tipe,
            'jurnals'=>$jurnals,
            'date'=>$this->date['date']->format('m/Y'),
        ]);
    }

    public function filter(Request $request){
        
        $my=Carbon::createFromFormat('m/Y', $request->date);
        $month = $my->month;
        $year = $my->year;
        
        $tipe=$request->get('tipe');
        
        $akuns = \App\Akun::where('id-tipe',$tipe->id)
            ->select('id','nama-akun')
            ->get();
        $jurnals = \App\Jurnal::where('id-tipe',$tipe->id)
            
            ->with('akunDebit')
            ->with('akunKredit')
            ->orderBy('tanggal','DESC')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->get();

        return view('jurnal', [
            'akuns'=>$akuns,
            'currentTipe'=>$tipe,
            'jurnals'=>$jurnals,
            'date'=>$request->date,
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
        try {
            $jurnal = new \App\Jurnal($data);
            $jurnal->{'id-tipe'} = $tipe->id;
            $jurnal->tanggal = Carbon::createFromFormat('d/m/Y', $data['tanggal'])->format('Y-m-d');
            $jurnal->save();
        }catch (QueryException $exception) {
            $this->flashError($exception->getMessage());
            return back();
        }

        $this->flashSuccess('Data Jurnal Berhasil Ditambahkan');
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
        $data = $request->validate([
            "tanggal" => "required",
            "keterangan" => "required",
            "id-debit" => "required",
            "debit" => "required",
            "id-kredit" => "required",
            "kredit" => "required",
        ]);
        try {
            $jurnal = \App\Jurnal::findOrFail($id);
            $jurnal->fill($data);
            $jurnal->tanggal = Carbon::createFromFormat('d/m/Y', $data['tanggal'])->format('Y-m-d');
            $jurnal->save();
        }catch (QueryException $exception) {
            $this->flashError($exception->getMessage());
            return back();
        }

        $this->flashSuccess('Data Jurnal Berhasil Diperbarui');
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $jurnal = \App\Jurnal::findOrFail($id);
            $jurnal->delete();
        }catch (QueryException $exception) {
            $this->flashError($exception->getMessage());
            return back();
        }
        
        $this->flashSuccess('Data Jurnal Berhasil Dihapus');
        return back();
    }

    public function validasi(Request $request){
        $ids = $request->input('id');
        foreach ($ids as $id) {
            $jurnal = \App\Jurnal::findOrFail($id);
            $jurnal->validasi = 1;
            
            $jurnal->save();
        }
        
        $this->flashSuccess('Data Jurnal Berhasil Divalidasi');
        return back();
    }

    public function excel(Request $request){
        
        $my=Carbon::createFromFormat('m/Y', $request->date);
        $month = $my->month;
        $year = $my->year;
        
        $tipe=$request->get('tipe');
        
        $akuns = \App\Akun::where('id-tipe',$tipe->id)
            ->select('id','nama-akun')
            ->get();
        $jurnal = \App\Jurnal::where('id-tipe',$tipe->id)
            ->with('akunDebit')
            ->with('akunKredit')
            ->orderBy('tanggal','ASC')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->get();
        
        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        
        $ex = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ex->getProperties()->setCreator("siannasGG");
        $ac = $ex->getActiveSheet();
        $ac->mergeCells('A1:I1');
        $ac->getCell('A1')->setValue("JURNAL ".(strtoupper($tipe->tipe)));
        $ac->getCell('A3')->setValue("PERIODE");
        
        $ac->getCell('B3')->setValue(": ".$bulan[$month-1]." ".$year);

        $ac->mergeCells('A6:A7');
        $ac->getCell('A6')->setValue("TANGGAL");
        $ac->mergeCells('B6:B7');
        $ac->getCell('B6')->setValue("NO. REF");
        $ac->mergeCells('C6:C7');
        $ac->getCell('C6')->setValue("KETERANGAN");
        $ac->mergeCells('D6:D7');
        $ac->getCell('D6')->setValue("NO. AKUN");
        $ac->mergeCells('E6:E7');
        $ac->getCell('E6')->setValue("NAMA AKUN");
        $ac->mergeCells('F6:F7');
        $ac->getCell('F6')->setValue("DEBIT");
        $ac->mergeCells('G6:G7');
        $ac->getCell('G6')->setValue("NO. AKUN");
        $ac->mergeCells('H6:H7');
        $ac->getCell('H6')->setValue("NAMA AKUN");
        $ac->mergeCells('I6:I7');
        $ac->getCell('I6')->setValue("KREDIT");
        
        // $ac->getCell('F8')->setValue(number_format($saldoAwal->saldo, 2));

        for($x=0;$x<count($jurnal);$x++){
            $ac->getCell('A'.($x+8))->setValue($jurnal[$x]->tanggal);
            $ac->getCell('B'.($x+8))->setValue($jurnal[$x]->{'no-ref'});
            $ac->getCell('C'.($x+8))->setValue($jurnal[$x]->keterangan);
            $ac->getCell('D'.($x+8))->setValue($jurnal[$x]->akunDebit->{'no-akun'});
            $ac->getCell('E'.($x+8))->setValue($jurnal[$x]->akunDebit->{'nama-akun'});
            $ac->getCell('F'.($x+8))->setValue(number_format($jurnal[$x]->debit,2));
            $ac->getCell('G'.($x+8))->setValue($jurnal[$x]->akunKredit->{'no-akun'});
            $ac->getCell('H'.($x+8))->setValue($jurnal[$x]->akunKredit->{'nama-akun'});
            $ac->getCell('I'.($x+8))->setValue(number_format($jurnal[$x]->kredit,2));
        }
        
        
        
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

        
        $col = $ac->getCellByColumnAndRow(9, 1)->getColumn();

        $ac->getColumnDimension('A')->setWidth(15);
        $ac->getColumnDimension('E')->setWidth(30);
        $ac->getColumnDimension('H')->setWidth(30);
        $ac->getColumnDimension('F')->setWidth(20);
        $ac->getColumnDimension('I')->setWidth(20);
        $ac->getColumnDimension('C')->setWidth(40);
        $ac->getStyle('A1')->applyFromArray($titleStyle);
        $ac->getStyle('A6:'.$col.(count($jurnal)+7))->applyFromArray($headerStyle);
        
        $fileName="Jurnal_".ucwords($tipe->tipe).".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($ex, 'Xlsx');
        $writer->save('php://output');
        exit;
        
    }
}
