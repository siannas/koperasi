<?php

use Illuminate\Database\Seeder;

class Akun_Kategori_Saldo_Seeder extends Seeder
{
    private $kategoris=[
        ['Kas','debit'],
        ['Bank','debit'],
        ['Piutang','debit'],
        ['Modal Unit Usaha','debit'],
        ['Aset Tidak Lancar','debit'],
        ['Peralatan Kantor','debit'],
        ['Kewajiban Jk Pendek','kredit'],
        ['Dana Pembagian SHU','kredit'],
        ['Kewajiban Jangka Panjang','kredit'],
        ['EKUITAS','kredit'],
        ['PENDAPATAN','kredit'],
        ['BPP. DAN BEBAN  LANGSUNG','debit'],
        ['BEBAN  ORGANISASI','debit'],
        ['BEBAN MANAJEMEN','debit'],
        ['PENDAPATAN LAIN-LAIN','debit']
    ];
    private $akuns=[
        [['1-1110','Kas Pusat'],
        ['1-1111','Kas USP'],
        ['1-1112','Kas Toko'],
        ['1-1113','Kas Foto Copy']],
        [['1-1210','Bank Jatim 1'],
        ['1-1220','Bank Jatim 2'],
        ['1-1230','Bank Jatim 3'],
        ['1-1240','Bank Jatim 4'],
        ['1-1250','Bank Bukopin 1'],
        ['1-1260','Bank Bukopin 2']],
        [['1-1310','Pinjaman USP'],
        ['1-1320','Pinjaman Toko'],
        ['1-1330','Pinjaman Foto Copy'],
        ['1-1340','Pinjaman Karyawan'],
        ['1-1350','Penyisihan Piutang Tak Tertagih'],
        ['1-1360','Persediaan Barang'],
        ['1-1370','Persediaan Titipan'],
        ['1-1380','Persediaan Kertas'],
        ['1-1390','Biaya dibayar dimuka']],
        [['1-2100','Modal Unit Simpan Pinjam'],
        ['1-2110','Modal Unit Toko'],
        ['1-2120','Modal Unit Fotocopy']],
        [['1-2210','Investasi Jangka Panjang'],
        ['1-2220','Aset Lain-lain']],
        [['1-2310','HP Peralatan Kantor'],
        ['1-2320','Akm. Peny. Peralatan Kantor']],
        [['2-1110','Hutang Usaha'],
        ['2-1120','Simpanan Sukarela'],
        ['2-1121','Simpanan Manasuka'],
        ['2-1122','Dana Resiko'],
        ['2-1123','Titipan Unit Foto Copy'],
        ['2-1124','Titipan Unit Toko'],
        ['2-1130','Beban YMH dibayar'],],
        [['2-1131','Dana Anggota'],
        ['2-1132','Dana Pengurus dan Pengawas'],
        ['2-1133','Dana Karyawan'],
        ['2-1134','Dana Pendidikan'],
        ['2-1135','Dana Sosial'],],
        [['2-2200','PT. Jatim Graha Utama'],
        ['2-2210','Hutang Bank'],
        ['2-2220','PT. Maspion'],
        ['2-2240','Hutang Smesco'],
        ['2-2250','Hutang Lain-lain'],],
        [['3-1000','Simpanan Pokok'],
        ['3-1050','Simpanan Wajib'],
        ['3-1100','Modal Donasi'],
        ['3-1150','Modal Umum'],
        ['3-1200','Modal Penyertaan'],
        ['3-1250','Cadangan Koperasi'],
        ['3-1300','SHU Tahun Lalu'],
        ['3-1350','SHU Periode Berjalan'],],
        [['4-1000','Pendapatan Bunga'],
        ['4-1100','Provisi'],
        ['4-1200','Pendapatan Pinjaman Barang'],
        ['4-1300','Deviden Saham'],
        ['4-1400','Penjualan Barang'],
        ['4-1500','Pendapatan Fotocopi'],
        ['4-1600','Pendapatan Fee'],
        ['4-1700','Pendapatan Lain-lain'],],
        [['5-1000','Beban Pokok Penjualan'],
        ['5-1001','Beban Transport Kulakan'],
        ['5-1002','Beban Barang Rusak/Hilang'],
        ['5-1003','Beban Kresek'],
        ['5-1004','Beban Sewa Mesin Foto Copy'],
        ['5-1005','Beban Kertas Foto Copy'],],
        [['5-1100','Gaji Pengurus dan Pengawas'],
        ['5-1200','Tunjangan Pengurus dan Pengawas'],
        ['5-1300','Beban Rapat Pengurus dan Pengawas'],
        ['5-1400','Konsumsi Rapat Pengurus dan Pengawas'],
        ['5-1500','Beban Transport Pengurus dan Pengawas'],
        ['5-1600','Beban Organisasi Lain-lain'],],
        [['6-1010','Gaji Karyawan'],
        ['6-1011','Tunjangan Karyawan'],
        ['6-1012','Beban Seragam'],
        ['6-1013','Beban Koordinasi Pemotong Gaji'],
        ['6-1014','Beban ATK'],
        ['6-1015','Beban Listrik & Telepon'],
        ['6-1016','Beban Transportasi'],
        ['6-1017','Beban Kebersihan'],
        ['6-1018','Beban Konsumsi'],
        ['6-1019','Beban Lembur'],
        ['6-1020','Beban Sumbangan'],
        ['6-1021','Beban Jasa Audit'],
        ['6-1022','Beban Penyusutan Aset'],
        ['6-1023','Beban Perawatan Aset'],
        ['6-1024','Beban Penyisihan Piutang Tak Tertagih'],
        ['6-1025','Beban Amortisasi Aset Lain-lain'],
        ['6-1026','Beban RK-RAPB / RAT'],
        ['6-1027','Beban Bunga Pinjaman'],
        ['6-1028','Beban Lain-lain'],],
        [['6-1100','Pendapatan Bunga Bank'],
        ['6-1110','Beban Pajak Bunga Bank'],
        ['6-1120','Beban Adm. Bank']]
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tipes=[];
        $slugs=['USP','FC','FK'];
        foreach (['simpan-pinjam', 'foto-copy', 'toko'] as $k=>$a) {
            $tipe = \App\Tipe::create([
                'tipe'=> $a,
                'slug'=> $slugs[$k],
            ]);
            array_push( $tipes, $tipe);
        }

        //data simpan-pinjam
        foreach ($this->akuns as $i => $a) {
            $kategori = $this->kategoris[$i];
            $kategori = \App\Kategori::create([
                'kategori'=> $kategori[0], 
                'tipe-pendapatan'=> $kategori[1],
            ]);
            foreach ($a as $aa) {
                $akun = \App\Akun::create([
                    'id-kategori'=> $kategori->id,
                    'id-tipe'=> $tipes[0]->id,
                    'no-akun'=>$aa[0],
                    'nama-akun'=>$aa[1],
                    'saldo'=>5000000
                ]);
                \App\Saldo::create([
                    'no-akun'=>$akun->{'no-akun'},
                    'id-tipe'=>$akun->{'id-tipe'},
                    'id-kategori'=>$akun->{'id-kategori'},
                    'saldo'=>$akun->saldo,
                    'tanggal'=>'2021-11-01'
                ]);
            }    
        }
    }
}
