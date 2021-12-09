<?php

namespace App\Console\Commands;

use App\Meta;
use App\Akun;
use App\Jurnal;
use App\LogJurnal;

use Carbon\Carbon;
use Illuminate\Console\Command;

class updateDashboard extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'updateDashboard';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'untuk melakukan rekap jurnal';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $my=Carbon::now();
        $month = $my->month;
        $year = $my->year;

        $jurnal_sp = Jurnal::where('id-tipe', 1)->whereMonth('tanggal', $month)->count();
        $meta_baru = Meta::where('key', 'jurnal_sp')->first();
        $meta_baru->value = $jurnal_sp;
        $meta_baru->save();

        $jurnal_fc = Jurnal::where('id-tipe', 2)->whereMonth('tanggal', $month)->count();
        $meta_baru = Meta::where('key', 'jurnal_fc')->first();
        $meta_baru->value = $jurnal_fc;
        $meta_baru->save();

        $jurnal_tk = Jurnal::where('id-tipe', 3)->whereMonth('tanggal', $month)->count();
        $meta_baru = Meta::where('key', 'jurnal_tk')->first();
        $meta_baru->value = $jurnal_tk;
        $meta_baru->save();

        $saldo_teratas = Akun::select('no-akun', 'nama-akun', 'saldo')->orderBy('saldo', 'desc')->take(5)->get();
        $meta_baru = Meta::where('key', '5_akun_ini')->first();
        $meta_baru->value = $saldo_teratas;
        $meta_baru->save();

        $aktivitas_baru = LogJurnal::select('keterangan', 'created_at')->orderBy('created_at', 'desc')->take(5)->get();
        $meta_baru = Meta::where('key', 'recent_activity')->first();
        $meta_baru->value = $aktivitas_baru;
        $meta_baru->save();
    }
}
