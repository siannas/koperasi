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

Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::get('/', 'DashboardController@dashboard');

    Route::prefix('{tipe}')->middleware(['customize.parameter'])->group(function(){
    
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
        Route::middleware(['role:Reguler-USP,Reguler-FC,Reguler-TK','strict.reguler'])->group(function(){
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

            Route::get('neraca', 'NeracaController@index')->name('neraca.index');
            Route::post('neraca', 'NeracaController@index')->name('neraca.filter');

            Route::get('buku-besar', 'BukuBesarController@index');
            Route::post('buku-besar', 'BukuBesarController@filter');
            Route::post('buku-besar/excel', 'BukuBesarController@excel');
        });
            

    });
    
    /*
    **  Admin Only: bisa create data master (master kategori, master akun) dan create user
    */
    Route::middleware(['role:Admin'])->group(function(){
        Route::resource('/akun', 'AkunController');
        Route::resource('/kategori', 'KategoriController');
    });
    
});
