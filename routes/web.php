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

Route::get('/', function () {
    return redirect('prenotazioni');
});
Route::resource('prenotazioni', 'PrenotazioniController')
    ->middleware('auth');

Route::put('prenotazioni/ritirato/{id}', 'PrenotazioniController@ritirato')
    ->middleware('auth');
Route::put('prenotazioni/nonritirato/{id}', 'PrenotazioniController@notRitirato')
    ->middleware('auth');

Route::put('prenotazioni/imbustato/{id}', 'PrenotazioniController@imbustato')
    ->middleware('auth');

Auth::routes();
