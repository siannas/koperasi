<?php

namespace App\Observers;

use App\Jurnal;
use App\LogJurnal;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class JurnalObserver
{
    private $userID;
    private $userNama;

    public function __construct(){
        $user = Auth::user();
        $this->userID = $user->id;
        $this->userNama = $user->nama;
    }

    /**
     * Handle the Jurnal "created" event.
     *
     * @param  \App\Jurnal  $jurnal
     * @return void
     */
    public function created(Jurnal $jurnal)
    {
        $d=[
            'id-user' => $this->userID,
            'transaksi' => 'create',
            'tipe' => $jurnal->{'id-tipe'},
            'jurnal-old' => null,
            'jurnal-now' => json_encode($jurnal),
            'keterangan' => "<b>{$this->userNama}</b> membuat jurnal <b>{$jurnal->keterangan}</b> sebesar <b>Rp ".number_format($jurnal->debit, 2)."</b>",
            'created_at' => Carbon::now()->toDateTimeString(),
        ];

        DB::beginTransaction();
        try {
            // $this->updateSaldoDebit($jurnal->{'id-debit'} , $jurnal->debit , $jurnal->tanggal, null, null, null);
            // $this->updateSaldoKredit($jurnal->{'id-kredit'} , $jurnal->kredit , $jurnal->tanggal, null, null, null);
            $logjurnal = new LogJurnal($d);
            $logjurnal->save();
        }catch (\Exception $exception) {
            DB::rollBack();
        }
        DB::commit();
    }

    /**
     * Handle the Jurnal "updating" event.
     *
     * @param  \App\Jurnal  $jurnal
     * @return void
     */
    public function updating(Jurnal $jurnal)
    {
        $old = Jurnal::find($jurnal->id);
        $d=[
            'id-user' => $this->userID,
            'tipe' => $jurnal->{'id-tipe'},
            'jurnal-old' => json_encode($old),
            'jurnal-now' => json_encode($jurnal),
            'created_at' => Carbon::now()->toDateTimeString(),
        ];

        if($old->validasi !== $jurnal->validasi){           //jika proses validasi atau unvalidasi
            if($jurnal->validasi === 1){                    //validasi
                $d['transaksi']='validate';
                $d['keterangan'] = "<b>{$this->userNama}</b> mengubah status jurnal <b>{$jurnal->keterangan}</b> menjadi <font color=\"green\"><b>tervalidasi</b></font>" ;
            }                                               //un-validasi
            else{                                          
                $d['transaksi']='unvalidate';
                $d['keterangan'] = "<b>{$this->userNama}</b> mengubah status jurnal <b>{$jurnal->keterangan}</b> menjadi <font color=\"red\"><b>belum tervalidasi</b></font>";
            }

            DB::beginTransaction();
            try {
                $this->updateSaldoDebit($jurnal->{'id-debit'} , $jurnal->debit , $jurnal->tanggal, $old->{'id-debit'}, $old->debit, $old->tanggal);
                $this->updateSaldoKredit($jurnal->{'id-kredit'} , $jurnal->kredit , $jurnal->tanggal, $old->{'id-kredit'}, $old->kredit, $old->tanggal);
                $logjurnal = new LogJurnal($d);
                $logjurnal->save();
            }catch (\Exception $exception) {
                DB::rollBack();
            }
            DB::commit();
        }   
        else{                   // jika ubah akun atau ubah nominal
            $d['transaksi']='update';
            $d['keterangan'] = "<b>{$this->userNama}</b> mengubah informasi jurnal <b>{$jurnal->keterangan}</b>";

            $logjurnal = new LogJurnal($d);
            $logjurnal->save();
        }
    }

    /**
     * Handle the Jurnal "deleting" event.
     *
     * @param  \App\Jurnal  $jurnal
     * @return void
     */
    public function deleting(Jurnal $jurnal)
    {
        $d=[
            'id-user' => $this->userID,
            'transaksi' => 'delete',
            'tipe' => $jurnal->{'id-tipe'},
            'jurnal-old' => json_encode($jurnal),
            'jurnal-now' => null,
            'keterangan' => "<b>{$this->userNama}</b> <font color=\"red\">menghapus</font> jurnal <b>{$jurnal->keterangan}</b> sebesar <b>Rp ".number_format($jurnal->debit, 2)."</b>",
            'created_at' => Carbon::now()->toDateTimeString(),
        ];

        
        DB::beginTransaction();
        try {
            // $this->updateSaldoDebit(null , null , null, $jurnal->{'id-debit'}, $jurnal->debit, $jurnal->tanggal);
            // $this->updateSaldoKredit(null , null , null, $jurnal->{'id-kredit'}, $jurnal->kredit, $jurnal->tanggal);
            $logjurnal = new LogJurnal($d);
            $logjurnal->save();
        }catch (\Exception $exception) {
            DB::rollBack();
        }
        DB::commit();   
    }

    /**
     * Handle perubahan saldo debit
     *
     * @param  Integer  $id_debit
     * @param  Float  $saldo
     * @param  Integer  $id_debit
     * @param  Float  $saldo_old
     * @return void
     */
    private function updateSaldoDebit($id_debit, $saldo, $tanggal, $id_debit_old, $saldo_old, $tanggal_old){
        $tanggal = substr($tanggal,0,8)."01";
        $tanggal_old = substr($tanggal_old,0,8)."01";
        if(isset($id_debit)){
            //dapatkan akun yg sesuai
            $akun=\App\Akun::where('id',$id_debit)->with('getKategori:id,tipe-pendapatan')->get(['id','id-tipe','saldo','id-kategori'])[0];
            $s=\App\Saldo::where('id-akun',$akun->id)->whereDate('tanggal',$tanggal)->first();
            
            if($s === NULL){
                $s = new \App\Saldo([
                    'saldo'=>$akun->saldo,
                    'id-akun'=>$akun->id,
                    'id-tipe'=>$akun->{'id-tipe'},
                    'id-kategori'=>$akun->{'id-kategori'},
                    'tanggal'=>$tanggal
                ]);
            }

            //pastikan tipe-pendapatan sesuai untuk menjumlah saldo debit
            ($akun->getKategori->{'tipe-pendapatan'}==='debit') ? $coef=1 : $coef=-1;
            $akun->saldo+=($coef*$saldo);
            $s->saldo+=($coef*$saldo);
            $akun->save();
            $s->save();
        }
        if(isset($id_debit_old)){
            //dapatkan akun yg sesuai
            $akun=\App\Akun::where('id',$id_debit_old)->with('getKategori:id,tipe-pendapatan')->get(['id','id-tipe','saldo','id-kategori'])[0];
            $s=\App\Saldo::where('id-akun',$akun->id)->whereDate('tanggal',$tanggal_old)->first();
            if($s === NULL){
                $s = new \App\Saldo([
                    'saldo'=>$akun->saldo,
                    'id-akun'=>$akun->id,
                    'id-tipe'=>$akun->{'id-tipe'},
                    'id-kategori'=>$akun->{'id-kategori'},
                    'tanggal'=>$tanggal_old
                ]);
            }

            //pastikan tipe-pendapatan sesuai untuk menjumlah saldo debit
            ($akun->getKategori->{'tipe-pendapatan'}==='debit') ? $coef=1 : $coef=-1;
            $akun->saldo-=($coef*$saldo_old);
            $s->saldo-=($coef*$saldo_old);
            $akun->save();
            $s->save();
        }
    }

    /**
     * Handle perubahan saldo kredit
     *
     * @param  Integer  $id_kredit
     * @param  Float  $saldo
     * @param  Integer  $id_kredit
     * @param  Float  $saldo_old
     * @return void
     */
    private function updateSaldoKredit($id_kredit, $saldo, $tanggal, $id_kredit_old, $saldo_old, $tanggal_old){
        $tanggal = substr($tanggal,0,8)."01";
        $tanggal_old = substr($tanggal_old,0,8)."01";
        if(isset($id_kredit)){
            //dapatkan akun yg sesuai
            $akun=\App\Akun::where('id',$id_kredit)->with('getKategori:id,tipe-pendapatan')->get(['id','id-tipe','saldo','id-kategori'])[0];
            $s=\App\Saldo::where('id-akun',$akun->id)->whereDate('tanggal',$tanggal)->first();
            if($s === NULL){
                $s = new \App\Saldo([
                    'saldo'=>$akun->saldo,
                    'id-akun'=>$akun->id,
                    'id-tipe'=>$akun->{'id-tipe'},
                    'id-kategori'=>$akun->{'id-kategori'},
                    'tanggal'=>$tanggal
                ]);
            }

            //pastikan tipe-pendapatan sesuai untuk menjumlah saldo debit
            ($akun->getKategori->{'tipe-pendapatan'}==='kredit') ? $coef=1 : $coef=-1;
            $akun->saldo+=($coef*$saldo);
            $s->saldo+=($coef*$saldo);
            $akun->save();
            $s->save();
        }
        if(isset($id_kredit_old)){
            //dapatkan akun yg sesuai
            $akun=\App\Akun::where('id',$id_kredit_old)->with('getKategori:id,tipe-pendapatan')->get(['id','id-tipe','saldo','id-kategori'])[0];
            $s=\App\Saldo::where('id-akun',$akun->id)->whereDate('tanggal',$tanggal_old)->first();
            if($s === NULL){
                $s = new \App\Saldo([
                    'saldo'=>$akun->saldo,
                    'id-akun'=>$akun->id,
                    'id-tipe'=>$akun->{'id-tipe'},
                    'id-kategori'=>$akun->{'id-kategori'},
                    'tanggal'=>$tanggal_old
                ]);
            }

            //pastikan tipe-pendapatan sesuai untuk menjumlah saldo debit
            ($akun->getKategori->{'tipe-pendapatan'}==='kredit') ? $coef=1 : $coef=-1;
            $akun->saldo-=($coef*$saldo_old);
            $s->saldo-=($coef*$saldo_old);
            $akun->save();
            $s->save();
        }
    }
}