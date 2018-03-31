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
Route::prefix('ticTacToe')->group(function () {
    Route::get('/', function () {
        return view('homepage');
    });

    Auth::routes();

    Route::get('/home', 'HomeController@index')->name('home');

    Route::get('/game', 'GameController@index')->name('game.index')->middleware('auth');

    Route::get('/history/store', 'GameHistoryController@store')->name('history.store')->middleware('auth');

    Route::resource('/game_history', 'GameHistoryController')->middleware('auth');

});
