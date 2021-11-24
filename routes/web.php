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

Route::prefix('{tipe}')->middleware(['auth','customize.parameter'])->group(function () {
    Route::get('/', function () {
        return view('dashboard');
    });  

    Route::resource('jurnal', 'JurnalController');

    Route::get('tes', 'TesController@index');
});

// Route::get('/home', 'HomeController@index')->name('home');
