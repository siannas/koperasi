<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// Route::middleware(['guest'])->group(function() {
    Auth::routes();
// });

Route::middleware(['auth'])->group(function() {
    # generator
    Route::get('/generateSaldo/{year}/{isValidated?}', 'SaldoController@GenerateSaldoByYear');
    Route::get('/generateSaldo/{month}/{year}/{isValidated?}', 'SaldoController@GenerateSaldoByMonth');
});

Route::middleware(['auth'])->prefix('{tahun}')->group(function () {
    Route::get('/', 'DashboardController@dashboard');

    Route::prefix('{tipe}')->middleware(['customize.parameter'])->group(function(){
        Route::post('jurnal/data', 'JurnalController@data')->name('jurnal.data');
    
        /*
        **  bisa akses semua pembukuan unit usaha (toko, simpan pinjam, fotokopi) 
        **  dapat memvalidasi entryan pada bulan yang tervalidasi agar terkunci. 
        **  Tidak bisa proses Create, Update, Delete
        */
        Route::middleware(['role:Supervisor'])->group(function(){
            
        });
        
        /*
        **  Hanya bisa mengakses 1 unit usaha saja. 
        */
        Route::middleware(['role:Spesial,Reguler-USP,Reguler-FC,Reguler-TK','strict.reguler'])->group(function(){
            Route::resource('jurnal', 'JurnalController')->except([
                'create','index',
            ]);
        });

        /*
        **  etc
        */
        Route::middleware(['role:Supervisor,Spesial,Reguler-USP,Reguler-FC,Reguler-TK','strict.reguler'])->group(function(){
            Route::resource('jurnal', 'JurnalController')->only([
                'index',
            ]);
            
            Route::post('jurnal/filter', 'JurnalController@filter')->name('jurnal.filter');
            Route::post('jurnal/excel', 'JurnalController@excel');
            Route::post('jurnal/validasi', 'JurnalController@validasi')->name('jurnal.validasi');

            Route::get('neraca', 'NeracaController@index')->name('neraca.index');
            Route::get('neraca', 'NeracaController@index')->name('neraca.filter');
            Route::get('neraca/excel', 'NeracaController@excel')->name('neraca.excel');
            Route::post('neraca/excel', 'NeracaController@excel')->name('neraca.download');

            Route::get('buku-besar', 'BukuBesarController@index');
            Route::post('buku-besar', 'BukuBesarController@filter');
            Route::post('buku-besar/excel', 'BukuBesarController@excel');

            Route::get('shu', 'SHUController@index')->name('shu.index');
            Route::get('shu', 'SHUController@index')->name('shu.filter');
            Route::get('shu/excel', 'SHUController@excel')->name('shu.excel');
            Route::post('shu/excel', 'SHUController@excel')->name('shu.download');
        });
            

    });
    
    /*
    **  Admin Only: bisa create data master (master kategori, master akun) dan create user
    */
    Route::middleware(['role:Admin'])->group(function(){
        Route::resource('/akun', 'AkunController');
        Route::resource('/kategori', 'KategoriController');
        Route::resource('user', 'DashboardController')->except([
            'create',
        ]);
        Route::get('/config_neraca', 'ConfigController@index');
        Route::post('/config_neraca', 'ConfigController@update')->name('configneraca.update');
    });

    /*
    **  Spesial Only: dapat melihat neraca gabungan antara ketiganya
    */
    Route::middleware(['role:Spesial,Supervisor'])->group(function(){
        Route::get('neraca', 'NeracaController@index')->name('neraca.index.gabungan');
        Route::get('neraca', 'NeracaController@index')->name('neraca.filter.gabungan');
        Route::get('neraca/excel', 'NeracaController@excel')->name('neraca.excel.gabungan');
        Route::post('neraca/excel', 'NeracaController@excel')->name('neraca.download.gabungan');

        Route::get('shu', 'SHUController@index')->name('shu.index.gabungan');
        Route::get('shu', 'SHUController@index')->name('shu.filter.gabungan');
        Route::get('shu/excel', 'SHUController@excel')->name('shu.excel.gabungan');
        Route::post('shu/excel', 'SHUController@excel')->name('shu.download.gabungan');
    });
    
    Route::middleware(['role:Supervisor'])->group(function(){
        Route::get('pengaturan', 'PengaturanController@index')->name('pengaturan.index');
        Route::put('pengaturan/datelock', 'PengaturanController@updateDateLock')->name('pengaturan.update.datelock');
    });
});
