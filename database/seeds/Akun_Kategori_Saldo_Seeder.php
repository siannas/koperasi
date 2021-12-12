<?php

use Illuminate\Database\Seeder;

class Akun_Kategori_Saldo_Seeder extends Seeder
{
    private $kategoris=[
        ['Kas','debit','NON-SHU'],
        ['Bank','debit','NON-SHU'],
        ['Piutang','debit','NON-SHU'],
        ['Modal Unit Usaha','debit','NON-SHU'],
        ['Aset Tidak Lancar','debit','NON-SHU'],
        ['Peralatan Kantor','debit','NON-SHU'],
        ['Kewajiban Jk Pendek','kredit','NON-SHU'],
        ['Dana Pembagian SHU','kredit','NON-SHU'],
        ['Kewajiban Jangka Panjang','kredit','NON-SHU'],
        ['EKUITAS','kredit','NON-SHU'],
        ['PENDAPATAN','kredit','SHU'],
        ['BPP. DAN BEBAN  LANGSUNG','debit','SHU'],
        ['BEBAN  ORGANISASI','debit','SHU'],
        ['BEBAN MANAJEMEN','debit','SHU'],
        ['PENDAPATAN LAIN-LAIN','debit','SHU']
    ];

    private $toko_kategoris =[['Kas Toko','debit','NON-SHU'],
        ['Bank','debit','NON-SHU'],
        ['Piutang','debit','NON-SHU'],
        ['Persediaan','debit','NON-SHU'],
        ['Biaya dibayar dimuka','debit','NON-SHU'],
        ['Inventaris','debit','NON-SHU'],
        ['Kewajiban Jk Pendek','kredit','NON-SHU'],
        ['Kewajiban Jangka Panjang','kredit','NON-SHU'],
        ['EKUITAS','kredit','NON-SHU'],
        ['SHU Periode Berjalan','kredit','NON-SHU'],
        ['PENDAPATAN','kredit','SHU'],
        ['BPP. DAN BEBAN  LANGSUNG','debit','SHU'],
        ['BEBAN MANAJEMEN','debit','SHU'],
        ['PENDAPATAN LAIN-LAIN','debit','SHU']];
    
    private $fc_kategoris=[
        ['Kas','debit','NON-SHU'],
        ['Titipan Kas ke Pusat','debit','NON-SHU'],
        ['Piutang','debit','NON-SHU'],
        ['Inventaris','debit','NON-SHU'],
        ['Kewajiban Jk Pendek','kredit','NON-SHU'],
        ['Kewajiban Jangka Panjang','kredit','NON-SHU'],
        ['EKUITAS','kredit','NON-SHU'],
        ['SHU Periode Berjalan','debit','NON-SHU'],
        ['PENDAPATAN','kredit','SHU'],
        ['BEBAN LANGSUNG','debit','SHU'],
        ['BEBAN MANAJEMEN','debit','SHU'],
        ['PENDAPATAN LAIN-LAIN','debit','SHU'],
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

    private $toko_akuns=[
        [['1-1110','Kas Toko']],
        [['1-1210','Bank Jatim 3']],
        [['1-1310','Piutang  Karyawan'],
        ['1-1311','Piutang  Anggota'],
        ['1-1312','Penyisihan Piutang Tak Tertagih']],
        [['1-1313','Persediaan Barang'],
        ['1-1314','Persediaan Titipan']],
        [['1-1342','Panjar Uang Receh'],
        ['1-1343','Beban di Bayar di Muka']],
        [['1-2410','HP Inventaris'],
        ['1-2420','Akum. Peny. Inventaris']],
        [['2-1110','Dana Resiko'],
        ['2-1120','Beban YMH dibayar'],
        ['2-1140','Hutang Usaha'],
        ['2-1150','Kewajiban Lain-lain']],
        [['2-2200','Hutang pada Bank'],
        ['2-2210','Hutang Smesco']],
        [['3-1000','Modal Usaha'],
        ['3-1200','Modal Donasi']],
        [['3-1350','SHU Periode Berjalan']],
        [['4-1000','Penjualan Barang'],
        ['4-1100','Pendapatan Pinjaman Barang'],
        ['4-1200','Pendapatan Lain-lain']],
        [['5-1000','Beban Pokok Penjualan'],
        ['5-1100','Beban Kresek'],
        ['5-1200','Beban Barang Rusak/Hilang'],
        ['5-1300','Beban Transport Kulakan']],
        [['6-1010','Gaji Karyawan'],
        ['6-1011','Tunjangan Karyawan'],
        ['6-1012','Beban ATK'],
        ['6-1013','Beban Listrik & Telepon'],
        ['6-1014','Beban Transportasi'],
        ['6-1015','Beban Konsumsi'],
        ['6-1016','Beban Lembur'],
        ['6-1017','Beban Perawatan Aset'],
        ['6-1018','Beban Penyusutan Aset'],
        ['6-1019','Beban Penyisihan Piutang Tak Tertagih'],
        ['6-1020','Beban Lain-lain']],
        [['6-1100','Pendapatan Bunga Bank'],
        ['6-1110','Beban Pajak Bunga Bank'],
        ['6-1120','Beban Adm. Bank']],
    ];

    private $fc_akuns=[
        [['1-1110','Kas']],
        [['1-1210','Setoran Kas']],
        [['1-1310','Piutang  Usaha'],
        ['1-1320','Penyisihan Piutang Tak Tertagih'],
        ['1-1330','Persediaan Kertas'],
        ['1-1340','Biaya dibayar dimuka']],
        [['1-2410','HP Inventaris'],
        ['1-2420','Akum. Peny. Inventaris']],
        [['2-1140','Dana Resiko'],
        ['2-1150','Beban YMH dibayar'],
        ['2-1160','Kewajiban Lain-lain']],
        [['2-2200','Hutang pada Bank'],
        ['2-2210','Hutang pada Pihak ke III']],
        [['3-1000','Modal Usaha'],
        ['3-1200','Modal Donasi']],
        [['3-1350','SHU Periode Berjalan']],
        [['4-1000','Pendapatan Fotocopi'],
        ['4-1100','Pendapatan Jasa'],
        ['4-1200','Pendapatan Lain-lain']],
        [['5-1000','Sewa Mesin Fotocopy'],
        ['5-1100','Kertas Fotocopy'],
        ['5-1200','Service dan Toner']],
        [['6-1010','Gaji Karyawan'],
        ['6-1020','Tunjangan Karyawan'],
        ['6-1040','Beban ATK'],
        ['6-1050','Beban Transportasi'],
        ['6-1080','Beban Konsumsi'],
        ['6-1090','Beban Perawatan Aset'],
        ['6-2000','Beban Penyisihan Piutang Tak Tertagih']],
        [['6-1100','Beban Pajak Bunga Bank'],
        ['6-1110','Beban Adm. Bank'],
        ['6-1120','Pendapatan Bunga Bank']],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Kategori::create([ 'kategori'=> 'SHU', ]);
        \App\Kategori::create([ 'kategori'=> 'NON-SHU', ]);

        $shu=\App\Kategori::where('kategori','SHU')->select('id','kategori')->first();
        $nonshu=\App\Kategori::where('kategori','NON-SHU')->select('id','kategori')->first();

        $tipes=[];
        $slugs=['USP','FC','FK'];
        foreach (['simpan-pinjam', 'foto-copy', 'toko'] as $k=>$a) {
            $tipe = \App\Tipe::create([
                'tipe'=> $a,
                'slug'=> $slugs[$k],
            ]);
            array_push( $tipes, $tipe);
        }
        
        $usp=\App\Tipe::where('slug','USP')->first();
        $fc=\App\Tipe::where('slug','FC')->first();
        $toko=\App\Tipe::where('slug','TK')->first();

        // //data simpan-pinjam
        foreach ($this->akuns as $i => $akuns) {
            $kategori_arr = $this->kategoris[$i];
            $kategori=\App\Kategori::where('kategori',$kategori_arr[0])->first();
            if( isset($kategori) === FALSE){
                $kategori = \App\Kategori::create([
                    'kategori'=> $kategori_arr[0], 
                    'tipe-pendapatan'=> $kategori_arr[1],
                    'parent'=> ($kategori_arr[2]==='SHU') ? $shu->id : $nonshu->id,
                ]);
            }
            foreach ($akuns as $a) {
                $akun = \App\Akun::create([
                    'id-kategori'=> $kategori->id,
                    'id-tipe'=> $toko->id,
                    'no-akun'=>$a[0],
                    'nama-akun'=>$a[1],
                    'saldo'=>2000000
                ]);
            } 
        }

        //data toko
        foreach ($this->toko_akuns as $i => $akuns) {
            $kategori_arr = $this->toko_kategoris[$i];
            $kategori=\App\Kategori::where('kategori',$kategori_arr[0])->first();
            if( isset($kategori) === FALSE){
                $kategori = \App\Kategori::create([
                    'kategori'=> $kategori_arr[0], 
                    'tipe-pendapatan'=> $kategori_arr[1],
                    'parent'=> ($kategori_arr[2]==='SHU') ? $shu->id : $nonshu->id,
                ]);
            }
            
            foreach ($akuns as $a) {
                $akun = \App\Akun::create([
                    'id-kategori'=> $kategori->id,
                    'id-tipe'=> $toko->id,
                    'no-akun'=>$a[0],
                    'nama-akun'=>$a[1],
                    'saldo'=>2000000
                ]);
            }    
        }

        //data foto copy
        foreach ($this->fc_akuns as $i => $akuns) {
            $kategori_arr = $this->fc_kategoris[$i];
            $kategori=\App\Kategori::where('kategori',$kategori_arr[0])->first();
            if( isset($kategori) === FALSE){
                $kategori = \App\Kategori::create([
                    'kategori'=> $kategori_arr[0], 
                    'tipe-pendapatan'=> $kategori_arr[1],
                    'parent'=> ($kategori_arr[2]==='SHU') ? $shu->id : $nonshu->id,
                ]);
            }
            
            foreach ($akuns as $a) {
                $akun = \App\Akun::create([
                    'id-kategori'=> $kategori->id,
                    'id-tipe'=> $fc->id,
                    'no-akun'=>$a[0],
                    'nama-akun'=>$a[1],
                    'saldo'=>2000000
                ]);
            }    
        }
    }
}
