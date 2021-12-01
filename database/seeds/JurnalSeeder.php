<?php

use Illuminate\Database\Seeder;

class JurnalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $idTipe=\App\Tipe::where('tipe','simpan-pinjam')->first();
        $debits=\App\Akun::where('id-tipe',$idTipe->id)->whereHas('getKategori', function ($q) {
                $q->where('tipe-pendapatan','debit');
            })->get();
        
        $kredits=\App\Akun::where('id-tipe',$idTipe->id)->whereHas('getKategori', function ($q) {
                $q->where('tipe-pendapatan','kredit');
            })->get();

        $debits_cnt=count($debits);
        $kredits_cnt=count($kredits);
        $min=100000;
        $max=3000000;

        for ($i=0; $i < 200; $i++) { 
            $money= mt_rand ($min*100, $max*100) / 100;
            \App\Jurnal::create([
                'id-tipe' => $idTipe->id,
                'id-debit' => $debits[rand(0,$debits_cnt-1)]->id, 
                'id-kredit' => $kredits[rand(0,$kredits_cnt-1)]->id,
                'no-ref' => null, 
                'debit' => $money,
                'kredit' => $money,
                'tanggal' => \Carbon\Carbon::now()->format('Y-m').'-'.rand(1,28),
                'keterangan' => 'Contoh jurnal nomor '.$i,
            ]);
        }
    }
}
