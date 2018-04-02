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
      return view('homepage');
  });

  Auth::routes();

  Route::get('/home', 'HomeController@index')->name('home');

  Route::get('/game', 'GameController@index')->name('game.index')->middleware('auth');
  Route::get('/game/new', 'GameController@newGame')->name('game.new')->middleware('auth');
  Route::get('/game/api/getGame/{id}', 'GameController@apiGetGame')->name('game.apiGetGame')->middleware('auth');
  Route::get('/game/browse', 'GameController@browse')->name('game.browse')->middleware('auth');
  Route::post('/game/winner', 'GameController@setWinner')->name('game.winner')->middleware('auth');

  Route::post('/cells/update', 'CellController@update')->name('cells.update')->middleware('auth');
