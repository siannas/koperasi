<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;

class JurnalController extends Controller
{
    private $ROLES_RANK=[
        'Pusat',
        // 'Spesial',
        'Reguler',
    ];

    private $filterDate = null;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->input('dateawal')) {
            $this->filterDate = Carbon::createFromLocaleIsoFormat('!MMMM/Y', 'id', $request->input('dateawal') . "/" . $this->year);
        } elseif ($request->cookie('date_month')) {
            $this->filterDate = Carbon::createFromLocaleIsoFormat('!MMMM/Y', 'id', $request->cookie('date_month') . "/" . $this->year);
        } else {
            $this->filterDate = Carbon::createFromLocaleIsoFormat('!M/Y', 'id', date('n') . "/" . $this->year);
        }
        Cookie::queue(Cookie::forever('date_month', $this->filterDate->translatedFormat('F')));
        $dateAkhir = clone($this->filterDate);
        $dateAkhir = $dateAkhir->addMonth();
        $dateAwal = $this->filterDate->format('Y-m-d');
        $dateAkhir=$dateAkhir->format('Y-m-d');
        $dateawal_raw=$dateAwal;
        $date_raw=$dateAkhir;

        $datelock = \App\Meta::where('key','setting_datelock')->pluck('value')->first();
        $datelock = Carbon::parse($datelock)->addMonth();
        
        $tipe=$request->get('tipe');

        // $byrole=array_intersect($this->ROLES_RANK,$request->get('roles'));
        $byrole=$this->ROLES_RANK;
        // $byrole=empty($byrole)?NULL:$byrole[0];

        $akuns = \App\Akun::select('id','nama-akun')
            ->get();
        return view('jurnal', [
            'dateawal' => $this->filterDate->translatedFormat('F'),
            'akuns'=>$akuns,
            'currentTipe'=>$tipe,
            // 'dateawal'=>$dateawal_raw,
            'date'=>$date_raw,
            'byrole'=>$byrole,
            'byroleFilter'=>$this->ROLES_RANK,
            'datelock'=>$datelock,
            'defaultDateInput'=>$this->filterDate->format('d/m/Y'),
        ]);
    }

    public function data(Request $request)
    {
        if ($request->input('dateawal')) {
            $this->filterDate = Carbon::createFromLocaleIsoFormat('!MMMM/Y', 'id', $request->input('dateawal') . "/" . $this->year);
        } elseif ($request->cookie('date_month')) {
            $this->filterDate = Carbon::createFromLocaleIsoFormat('!MMMM/Y', 'id', $request->cookie('date_month') . "/" . $this->year);
        } else {
            $this->filterDate = Carbon::createFromLocaleIsoFormat('!M/Y', 'id', date('n') . "/" . $this->year);
        }

        $limit = $request->input('length');
        $start = $request->input('start');
        $order = $request->input('order.0.column');
        $dir = $request->input('order.0.dir');
        $search = $request->input('search.value');
        
        $dateAkhir = clone($this->filterDate);
        $dateAkhir = $dateAkhir->addMonth();
        $dateAwal = $this->filterDate->format('Y-m-d');
        $dateAkhir=$dateAkhir->format('Y-m-d');

        $tipe=$request->get('tipe');

        $byrole=$this->ROLES_RANK;
        $columns = ["id", "tanggal", "no-ref", "keterangan", "akun_debit", "debit", "akun_kredit", "kredit", "validasi"];
        if (isset($order)) {
            $order = $columns[$order];
        }
        $query = DB::query()->fromSub(function ($query) use ($tipe, $dateAwal, $dateAkhir) {
            $query->from('jurnal')->select(
                "jurnal.id AS id",
                "tanggal",
                DB::Raw("DATE_FORMAT(tanggal, '%d/%m/%Y') AS tanggal_formatted"),
                "no-ref",
                "keterangan",
                DB::Raw("CONCAT(akunDebit.`no-akun`,' - ', akunDebit.`nama-akun`) AS akun_debit"),
                "debit",
                "id-debit",
                DB::Raw("CONCAT(akunKredit.`no-akun`,' - ', akunKredit.`nama-akun`) AS akun_kredit"),
                "kredit",
                "id-kredit",
                "validasi",
                DB::Raw("IFNULL(`by-role`, 'Reguler') AS 'by-role'"),
            )
            ->where('jurnal.id-tipe',$tipe->id)
            ->leftJoin('akun as akunDebit', 'akunDebit.id', '=', 'id-debit')
            ->leftJoin('akun as akunKredit', 'akunKredit.id', '=', 'id-kredit')
            ->whereDate('tanggal','>=',$dateAwal)
            ->whereDate('tanggal','<=',$dateAkhir);
        }, 'a')
        ->when(isset($order), function($q) use ($order, $dir) {
                $q->orderBy($order, $dir)->orderBy('id','DESC');
            }, function($q) {
                $q->orderBy('tanggal','DESC')->orderBy('id','DESC');
        })
        ->when(!empty($search), function($q) use ($search) {
            $q->where('tanggal_formatted', 'LIKE', "%{$search}%")
            ->orWhere('no-ref', 'LIKE', "%{$search}%")
            ->orWhere('keterangan', 'LIKE', "%{$search}%")
            ->orWhere('akun_debit', 'LIKE', "%{$search}%")
            ->orWhere('debit', 'LIKE', "%{$search}%")
            ->orWhere('akun_kredit', 'LIKE', "%{$search}%")
            ->orWhere('kredit', 'LIKE', "%{$search}%")
            ->orWhere('validasi', 'LIKE', "%{$search}%")
            ->orWhere('by-role', 'LIKE', "%{$search}%");
        });
        $totalFiltered = $query->count();
        $jurnals = $query->offset($start)->limit($limit)->get();
        return response()->json([
            "draw" => intval($request->input('draw')),
            "recordsFiltered" => intval($totalFiltered),
            "data" => $jurnals
        ]);
    }

    public function filter(Request $request){
        $tipe=$request->get('tipe');
        return redirect(route( 'jurnal.index',['tipe'=>$tipe->tipe])."?dateawal={$request->dateawal}");
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
        $today = Carbon::today();
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
            $byrole=array_intersect($this->ROLES_RANK,$request->get('roles'));
            $jurnal = new \App\Jurnal($data);

            // set by-role pada jurnal
            if(empty($byrole)===FALSE){
                $jurnal->{'by-role'}=$byrole[0];
            }
            
            $jurnal->{'id-tipe'} = $tipe->id;
            $jurnal->tanggal = Carbon::createFromFormat('d/m/Y', $data['tanggal'])->format('Y-m-d');
            $date = Carbon::createFromFormat('d/m/Y', $data['tanggal']);
            $datelock = \App\Meta::where('key','setting_datelock')->pluck('value')->first();
            $datelock = Carbon::parse($datelock)->addMonth();
            
            // Jika pengisian lebih dari today
            if($jurnal->tanggal > $today){
                $this->flashError('Tanggal Melebihi Hari Ini: '.$today->isoFormat('D MMMM Y'));
                return redirect(url($this->year.'/'.$tipe->tipe.'/jurnal')."?dateawal={$request->dateawal}&date={$request->date}");
            }
            // Jika selisih pengisian & today lebih dari DATE LOCK
            elseif($date->lessThan($datelock)){
                
                $this->flashError('Tanggal Sudah Melewati Batas Waktu Pengisian');
                return redirect(url($this->year.'/'.$tipe->tipe.'/jurnal')."?dateawal={$request->dateawal}&date={$request->date}");
            }
            $jurnal->save();
        }catch (QueryException $exception) {
            $this->flashError($exception->getMessage());
            return redirect(url($this->year.'/'.$tipe->tipe.'/jurnal')."?dateawal={$request->dateawal}&date={$request->date}");
        }

        $this->flashSuccess('Data Jurnal Berhasil Ditambahkan');
        return back();
        // return redirect(url($this->year.'/'.$tipe->tipe.'/jurnal')."?dateawal={$request->dateawal}&date={$request->date}");
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
    public function update(Request $request, $year, $id)
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
            $jurnal = \App\Jurnal::findOrFail($id);
            $jurnal->fill($data);
            $jurnal->tanggal = Carbon::createFromFormat('d/m/Y', $data['tanggal'])->format('Y-m-d');
            $jurnal->save();
        }catch (QueryException $exception) {
            $this->flashError($exception->getMessage());
            return redirect(url($this->year.'/'.$tipe->tipe.'/jurnal')."?dateawal={$request->dateawal}&date={$request->date}");
        }

        $this->flashSuccess('Data Jurnal Berhasil Diperbarui');
        return back();
        // return redirect(url($this->year.'/'.$tipe->tipe.'/jurnal')."?dateawal={$request->dateawal}&date={$request->date}");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $year, $id)
    {
        $tipe=$request->get('tipe');
        try {
            $jurnal = \App\Jurnal::findOrFail($id);
            $jurnal->delete();
        }catch (QueryException $exception) {
            $this->flashError($exception->getMessage());
            return redirect(url($this->year.'/'.$tipe->tipe.'/jurnal')."?dateawal={$request->dateawal}&date={$request->date}");
        }
        
        $this->flashSuccess('Data Jurnal Berhasil Dihapus');
        return back();
        // return redirect(url($this->year.'/'.$tipe->tipe.'/jurnal')."?dateawal={$request->dateawal}&date={$request->date}");
    }

    public function validasi(Request $request){
        $tipe=$request->get('tipe');
        $ids = $request->input('id');
        foreach ($ids as $id) {
            $jurnal = \App\Jurnal::findOrFail($id);
            if($jurnal->validasi == 0){
                $jurnal->validasi = 1;
            }
            else{
                $jurnal->validasi *= -1;
            }
            $jurnal->save();
        }
        
        $this->flashSuccess('Status Validasi Pada Data Jurnal Berhasil Diubah');
        return back();
        // return redirect(url($this->year.'/'.$tipe->tipe.'/jurnal')."?dateawal={$request->dateawal}&date={$request->date}");
    }

    public function excel(Request $request){
        $my = Carbon::createFromLocaleIsoFormat('!MMMM/Y', 'id', $request->input('dateawal') . "/" . $this->year);
        // $my=Carbon::createFromFormat('d/m/Y', $request->date);
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

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath(base_path('public/img/logo.png'));
        $drawing->setCoordinates('C1');
        $drawing->setOffsetX(180);
        $drawing->setOffsetY(5);
        $drawing->setHeight(80);
        $drawing->setWorksheet($ex->getActiveSheet());

        $ac->mergeCells('C1:I1');
        $ac->getCell('C1')->setValue("KOPERASI KONSUMEN PEGAWAI REPUBLIK INDONESIA");
        $ac->mergeCells('C2:I2');
        $ac->getCell('C2')->setValue("SEKRETARIAT DAERAH TINGKAT PROVINSI JAWA TIMUR");
        $ac->mergeCells('C3:I3');
        $ac->getCell('C3')->setValue("Jl. PAHLAWAN   No. 110   TELP. (031) 3524001-11  Ps. 1516, 1514, 1519 ");
        $ac->mergeCells('C4:I4');
        $ac->getCell('C4')->setValue("S U R A B A Y A");

        $ac->mergeCells('A6:I6');
        $ac->getCell('A6')->setValue("JURNAL ".(strtoupper($tipe->tipe)));
        $ac->mergeCells('A7:I7');
        $ac->getCell('A7')->setValue("Periode ".$bulan[$month-1]." ".$year);

        $ac->mergeCells('A10:A11');
        $ac->getCell('A10')->setValue("TANGGAL");
        $ac->mergeCells('B10:B11');
        $ac->getCell('B10')->setValue("NO. REF");
        $ac->mergeCells('C10:C11');
        $ac->getCell('C10')->setValue("KETERANGAN");
        $ac->mergeCells('D10:D11');
        $ac->getCell('D10')->setValue("NO. AKUN");
        $ac->mergeCells('E10:E11');
        $ac->getCell('E10')->setValue("NAMA AKUN");
        $ac->mergeCells('F10:F11');
        $ac->getCell('F10')->setValue("DEBIT");
        $ac->mergeCells('G10:G11');
        $ac->getCell('G10')->setValue("NO. AKUN");
        $ac->mergeCells('H10:H11');
        $ac->getCell('H10')->setValue("NAMA AKUN");
        $ac->mergeCells('I10:I11');
        $ac->getCell('I10')->setValue("KREDIT");
        
        // $ac->getCell('F8')->setValue(indo_num_format($saldoAwal->saldo, 2));

        for($x=0;$x<count($jurnal);$x++){
            $ac->getCell('A'.($x+12))->setValue($jurnal[$x]->tanggal);
            $ac->getCell('B'.($x+12))->setValue($jurnal[$x]->{'no-ref'});
            $ac->getCell('C'.($x+12))->setValue($jurnal[$x]->keterangan);
            $ac->getCell('D'.($x+12))->setValue($jurnal[$x]->akunDebit->{'no-akun'});
            $ac->getCell('E'.($x+12))->setValue($jurnal[$x]->akunDebit->{'nama-akun'});
            $ac->getCell('F'.($x+12))->setValue(indo_num_format($jurnal[$x]->debit,2));
            $ac->getCell('G'.($x+12))->setValue($jurnal[$x]->akunKredit->{'no-akun'});
            $ac->getCell('H'.($x+12))->setValue($jurnal[$x]->akunKredit->{'nama-akun'});
            $ac->getCell('I'.($x+12))->setValue(indo_num_format($jurnal[$x]->kredit,2));
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

        $title3Style = [
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
            'font' => [
                'bold' => true,
                'size' => 12,
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
        $ac->getStyle('C1')->applyFromArray($titleStyle);
        $ac->getStyle('A6')->applyFromArray($titleStyle);
        $ac->getStyle('A2:I4')->applyFromArray($title2Style);
        $ac->getStyle('A7')->applyFromArray($title3Style);
        $ac->getStyle('A10:'.$col.(count($jurnal)+11))->applyFromArray($headerStyle);
        
        $fileName="Jurnal_".ucwords($tipe->tipe)."_".$request->date.".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="'. urlencode($fileName).'"');
        header('Cache-Control: max-age=0');
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($ex, 'Xlsx');
        $writer->save('php://output');
        exit;
        
    }

    public function jurnalImport(Request $request)
    {
        try {
            $service = new \App\Http\Controllers\Jurnal\JurnalImport($request);
            $service->execute();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
