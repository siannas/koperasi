<?php

namespace App\Observers;

use App\Jurnal;
use App\LogJurnal;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

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

        $logjurnal = new LogJurnal($d);
        $logjurnal->save();
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
        }   
        else{                   // jika ubah akun atau ubah nominal
            $d['transaksi']='update';
            $d['keterangan'] = "<b>{$this->userNama}</b> mengubah informasi jurnal <b>{$jurnal->keterangan}</b>";
        }

        $logjurnal = new LogJurnal($d);
        $logjurnal->save();
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

        $logjurnal = new LogJurnal($d);
        $logjurnal->save();
    }
}
