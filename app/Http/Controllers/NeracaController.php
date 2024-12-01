<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class NeracaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($tipe=$request->get('tipe')){
            $data = $this->init($request, $tipe);
        }else{
            $data = $this->initGabungan($request);
        }
        
        return view('neraca', $data);
    }

    /**
     * Download excel neraca.
     *
     * @return \Illuminate\Http\Response
     */
    public function excel(Request $request, $cmd=NULL){
        if($tipe=$request->get('tipe')){
            $d = $this->init($request, $tipe);
        }else{
            $d = $this->initGabungan($request);
        }

        $tanggalString = Carbon::createFromFormat('m/Y', $d['date'])->isoFormat('MMMM Y');
        
        $ex = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $ex->getProperties()->setCreator("siannasGG");
        $ac = $ex->getActiveSheet();

        $ac->getStyle('C:E')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);
        $ac->getStyle('H:J')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT);

        // KOP
        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setPath( $cmd==='view-gabungan' ? asset('img/logo.png') : asset('img/logo.png'));
        $drawing->setCoordinates('C1');
        $drawing->setOffsetX(70);
        $drawing->setOffsetY(5);
        $drawing->setHeight(80);
        $drawing->setWorksheet($ex->getActiveSheet());

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
        $ac->getCell('B7')->setValue("Periode ".$tanggalString);
        $ac->getStyle('B7:J7')->getFont()->setSize(12)->setBold(true);
        $ac->getStyle('B7:J7')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);;

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
        foreach($d['kategoris_debit'] as $k => $kd){
            if($kd->getAkun()->get()->isEmpty() === false){
                $saldo_berjalan=0;
                $saldo_awal=0;

                $visited=[];
                $visited2=[];
                foreach($kd->getAkun()->get() as $akun){
                    $visited[ $akun->{'nama-akun'} ][]=$akun->id;
                }
                // display per kategori
                $now=$walk;
                foreach($kd->getAkun()->get() as $akun){
                    // pastikan nama akun belum di-visit (guna view gabungan untuk nama akun yg sama)
                    if(array_key_exists($akun->{'nama-akun'} , $visited2) === FALSE){
                        $walk++;

                        $awal=0;
                        $cur=0;
                        //gabungin saldo semua akun yang memiliki nama yang kembar.
                        foreach($visited[$akun->{'nama-akun'} ] as $id_ak ){
                            $debit=$d['jurnal_debit']->has($id_ak) ? $d['jurnal_debit'][$id_ak]->debit : 0;
                            $kredit=$d['jurnal_kredit']->has($id_ak) ? $d['jurnal_kredit'][$id_ak]->kredit : 0;
                            $cur+=$debit-$kredit;
                            $awal+=$d['saldos']->has($id_ak) ? $d['saldos'][$id_ak]->saldo : 0;
                        }
                        $saldo_awal+=$awal;
                        $saldo_berjalan+=$cur;

                        $ac->getCell('B'.($from+$walk))->setValue( "      ".$akun->{'nama-akun'} );
                        $ac->getCell('C'.($from+$walk))->setValue( number_format($awal,2) );
                        $ac->getCell('D'.($from+$walk))->setValue( number_format($cur,2) );
                        $ac->getCell('E'.($from+$walk))->setValue( number_format($awal+$cur,2) );
                        $ac->getStyle("B".($from+$walk).":E".($from+$walk))->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F2F2F2');

                        $visited2[ $akun->{'nama-akun'} ]=1;
                    }
                }

                // display total saldo kategori
                $row = $from+$now;
                $ac->getCell('B'.($row))->setValue( $kd->kategori );
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
        foreach($d['kategoris_kredit'] as $k => $kd){
            if($kd->getAkun()->get()->isEmpty() === false){
                $saldo_berjalan=0;
                $saldo_awal=0;

                $visited=[];
                $visited2=[];
                foreach($kd->getAkun()->get() as $akun){
                    $visited[ $akun->{'nama-akun'} ][]=$akun->id;
                }
                // display per kategori kewajiban
                $now=$walk;
                foreach($kd->getAkun()->get() as $akun){
                    if (array_key_exists($akun->{'nama-akun'}, $visited2) === false) {
                        $walk++;

                        $awal=0;
                        $cur=0;
                        foreach($visited[$akun->{'nama-akun'} ] as $id_ak ){
                            $debit=$d['jurnal_debit']->has($id_ak) ? $d['jurnal_debit'][$id_ak]->debit : 0;
                            $kredit=$d['jurnal_kredit']->has($id_ak) ? $d['jurnal_kredit'][$id_ak]->kredit : 0;
                            $cur=$kredit-$debit;
                            $awal+=$d['saldos']->has($id_ak) ? $d['saldos'][$id_ak]->saldo : 0;
                        }
                        $saldo_awal+=$awal;
                        $saldo_berjalan+=$cur;

                        $ac->getCell('G'.($from+$walk))->setValue("      ".$akun->{'nama-akun'});
                        $ac->getCell('H'.($from+$walk))->setValue(number_format($awal, 2));
                        $ac->getCell('I'.($from+$walk))->setValue(number_format($cur, 2));
                        $ac->getCell('J'.($from+$walk))->setValue(number_format($awal+$cur, 2));
                        $ac->getStyle("G".($from+$walk).":J".($from+$walk))->getFill()
                            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                            ->getStartColor()->setRGB('F2F2F2');
                        
                        $visited2[ $akun->{'nama-akun'} ]=1;
                    }
                }
                

                // display total saldo kategori kewajiban
                $row = $from+$now;
                $ac->getCell('G'.($row))->setValue( $kd->kategori );
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


        if($cmd==='view-gabungan'){
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Html($ex);
            // $header = $writer->generateHTMLHeader();
            echo $writer->generateStyles();
            echo "<style>.gridlines td {border: 0;}</style>\n</head>";
            echo $writer->generateSheetData();
            echo $writer->generateHTMLFooter();
        }else{
            // send file ke user
            $fileName="Neraca_".$tanggalString.".xlsx";
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
    private function init(Request $request, $tipe){
        //dapetin tanggal neraca yg ingin di download
        $month = $this->date['m'];
        $year = $this->date['y'];
        $filter = Carbon::instance($this->date['date']);
        // $filter->day = 1;
        // if($request->input('date')){
        //     $my=Carbon::createFromFormat('m/Y', $request->input('date'));
        //     $month = $my->month;
        //     $year = $my->year;
        //     $filter=$my;
        // }
        if($request->input('date_month')){
            $my=Carbon::createFromLocaleIsoFormat('!MMMM/Y', 'id', $request->post('date_month') . "/" . $request->post('date_year'));
            $month = $my->month;
            $year = $my->year;
            $filter=$my;
        }
        $filter->day = 1;
        $saldosIds=\App\Saldo::select(DB::raw('COUNT(`id-akun`) cnt'),DB::raw('MAX(`id`) id'))
            ->whereDate('tanggal','<',$filter->format('Y-m-d'))
            ->groupBy('id-akun')->pluck('id');
        $saldos=\App\Saldo::select('id','id-kategori','id-akun','saldo')
            ->whereIn('id',$saldosIds)
            ->get()->keyBy('id-akun');

        // Ambil kategori parent yang non SHU
        $nonSHU=\App\Kategori::where('kategori','NON-SHU')->select('id')->first();
        
        $kategoris_debit=\App\Kategori::with(['getAkun' => function($q) use($tipe) {
                /** NOTE:
                 * ambil akun dengan string nama yang sama
                 * */ 
                $q->select('B.id','akun.nama-akun','akun.no-akun', 'akun.id-kategori','akun.id-tipe')
                    ->leftJoin('akun AS B','akun.nama-akun','LIKE','B.nama-akun')
                    ->where(function($q2) use($tipe){
                        $q2->where('akun.id-tipe',$tipe->id)
                           ->where('akun.id','=',DB::raw('B.id'));
                    })
                    ->orWhere(function($q2) use($tipe){
                        $q2->where('B.id-tipe','<>',$tipe->id)
                            ->where('akun.id','<>',DB::raw('B.id'));
                            // ->whereNotNull('B.nama-akun');
                    });
            }])
            ->where('tipe-pendapatan', 'debit')
            ->where('parent',$nonSHU->id)
            ->orderBy('priority','ASC')
            ->get();

        $kategoris_kredit=\App\Kategori::with(['getAkun' => function($q) use($tipe) {
                /** NOTE:
                 * ambil akun dengan string nama yang sama
                 * */ 
                $q->select('B.id','akun.nama-akun','akun.no-akun', 'akun.id-kategori','akun.id-tipe')
                    ->leftJoin('akun AS B','akun.nama-akun','LIKE','B.nama-akun')
                    ->where(function($q2) use($tipe){
                        $q2->where('akun.id-tipe',$tipe->id)
                           ->where('akun.id','=',DB::raw('B.id'));
                    })
                    ->orWhere(function($q2) use($tipe){
                        $q2->where('B.id-tipe','<>',$tipe->id)
                            ->where('akun.id','<>',DB::raw('B.id'));
                            // ->whereNotNull('B.nama-akun');
                    });
            }])
            ->where('tipe-pendapatan', 'kredit')
            ->where('parent',$nonSHU->id)
            ->orderBy('priority','ASC')
            ->get();

        $jurnal_debit=\App\Jurnal::leftJoin('akun AS A','id-debit','LIKE','A.id')
            ->leftJoin('akun AS B','A.nama-akun','LIKE','B.nama-akun')
            ->selectRaw('`id-debit`, sum(debit) as debit')
            ->groupBy('id-debit')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('validasi',1)
            ->where(function($q) use($tipe){
                $q->where(function($q2) use($tipe){
                        $q2->where('A.id-tipe',$tipe->id)
                        ->where('A.id','=',DB::raw('B.id'));
                    })
                    ->orWhere(function($q2) use($tipe){
                        $q2->where('A.id-tipe','<>',$tipe->id)
                            ->where('A.id','<>',DB::raw('B.id'));
                    });
            })
            ->get()->keyBy('id-debit');

        $jurnal_kredit=\App\Jurnal::leftJoin('akun AS A','id-kredit','LIKE','A.id')
            ->leftJoin('akun AS B','A.nama-akun','LIKE','B.nama-akun')
            ->selectRaw('`id-kredit`, sum(kredit) as kredit')
            ->groupBy('id-kredit')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('validasi',1)
            ->where(function($q) use($tipe){
                $q->where(function($q2) use($tipe){
                        $q2->where('A.id-tipe',$tipe->id)
                        ->where('A.id','=',DB::raw('B.id'));
                    })
                    ->orWhere(function($q2) use($tipe){
                        $q2->where('A.id-tipe','<>',$tipe->id)
                            ->where('A.id','<>',DB::raw('B.id'));
                    });
            })
            ->get()->keyBy('id-kredit');
    
        return [
            'currentTipe'=>$tipe,
            'date'=> $month.'/'.$year,
            'month'=> $filter->localeMonth,
            'year' => $year,
            'kategoris_debit' => $kategoris_debit,
            'kategoris_kredit' => $kategoris_kredit,
            'jurnal_debit' => $jurnal_debit,
            'jurnal_kredit' => $jurnal_kredit,
            'saldos'=>$saldos,
        ];
    }

    /**
     * Init data-data.
     *
     * @return Object
     */
    private function initGabungan(Request $request){
        //dapetin tanggal neraca yg ingin di download
        $month = $this->date['m'];
        $year = $this->date['y'];
        $filter = Carbon::instance($this->date['date']);
        // $filter->day = 1;
        if($request->post('date_month')){
            $my=Carbon::createFromLocaleIsoFormat('!MMMM/Y', 'id', $request->post('date_month') . "/" . $request->post('date_year'));
            $month = $my->month;
            $year = $my->year;
            $filter=$my;
        }
        $filter->day = 1;
        
        $saldosIds=\App\Saldo::select(DB::raw('COUNT(`id-akun`) cnt'),DB::raw('MAX(`id`) id'))
            ->whereDate('tanggal','<',$filter->format('Y-m-d'))
            ->groupBy('id-akun')->pluck('id');
        $saldos=\App\Saldo::select('id','id-kategori','id-akun','saldo')
            ->whereIn('id',$saldosIds)
            ->get()->keyBy('id-akun');

        // Ambil kategori parent yang non SHU
        $nonSHU=\App\Kategori::where('kategori','NON-SHU')->select('id')->first();
        
        $kategoris_debit=\App\Kategori::with(['getAkun' => function($q){
                $q->select('a2.id','a2.nama-akun','a2.no-akun', 'akun.id-kategori','a2.id-tipe')
                    ->rightJoin(DB::raw('akun a2'), 'akun.nama-akun','=','a2.nama-akun');
            }])
            ->where('tipe-pendapatan', 'debit')
            ->where('parent',$nonSHU->id)
            ->orderBy('priority','ASC')
            ->get();

        $kategoris_kredit=\App\Kategori::with(['getAkun' => function($q){
                $q->select('a2.id','a2.nama-akun','a2.no-akun', 'akun.id-kategori','a2.id-tipe')
                    ->rightJoin(DB::raw('akun a2'), 'akun.nama-akun','=','a2.nama-akun');
            }])
            ->where('tipe-pendapatan', 'kredit')
            ->where('parent',$nonSHU->id)
            ->orderBy('priority','ASC')
            ->get();

        $jurnal_debit=\App\Jurnal::selectRaw('`id-debit`, sum(debit) as debit')
            ->groupBy('id-debit')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('validasi',1)
            ->get()->keyBy('id-debit');

        $jurnal_kredit=\App\Jurnal::selectRaw('`id-kredit`, sum(kredit) as kredit')
            ->groupBy('id-kredit')
            ->whereMonth('tanggal', $month)
            ->whereYear('tanggal', $year)
            ->where('validasi',1)
            ->get()->keyBy('id-kredit');
    
        return [
            'currentTipe'=>NULL,
            'date'=> $month.'/'.$year,
            'month'=> $filter->localeMonth,
            'year' => $year,
            'kategoris_debit' => $kategoris_debit,
            'kategoris_kredit' => $kategoris_kredit,
            'jurnal_debit' => $jurnal_debit,
            'jurnal_kredit' => $jurnal_kredit,
            'saldos'=>$saldos,
        ];
    }
}
