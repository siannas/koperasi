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
        Route::resource('jurnal', 'JurnalController');

        Route::get('tes', 'TesController@index');
    });
    // Route::get('/akun', 'AkunController@index');
    Route::resource('/akun', 'AkunController');
    
});
