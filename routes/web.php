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
        Route::resource('jurnal', 'JurnalController')->except([
            'create',
        ]);

        Route::get('neraca', 'NeracaController@index')->name('neraca.index');
        Route::post('neraca', 'NeracaController@index')->name('neraca.filter');

        Route::get('buku-besar', 'BukuBesarController@index');
        Route::post('buku-besar', 'BukuBesarController@filter');
        Route::post('buku-besar/excel', 'BukuBesarController@excel');
        Route::get('tes', 'TesController@index');
    });
    
    Route::resource('/akun', 'AkunController');
    Route::resource('/kategori', 'KategoriController');
});
