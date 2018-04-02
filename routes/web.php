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
  //Auth Routes
  Auth::routes();

  //HomePage and Home (Profile)
  Route::get('/', function () {
      return view('homepage');
  })->name('homepage');

  Route::get('/home', 'HomeController@index')->name('home');

  //Game
  Route::get('/game', 'GameController@index')->name('game.index')->middleware('auth');
  Route::get('/game/new', 'GameController@newGame')->name('game.new')->middleware('auth');
  Route::get('/game/api/get/{id}', 'GameController@apiGetGame')->name('game.apiGetGame')->middleware('auth');

  Route::get('/game/browse', 'GameController@browse')->name('game.browse')->middleware('auth');
  Route::post('/game/winner', 'GameController@setWinner')->name('game.winner')->middleware('auth');


  Route::get('/history', 'GameController@getHistory')->name('history')->middleware('auth');
  Route::get('/history/destroy', 'GameController@destroyHistory')->name('history.destroy')->middleware('auth');

  //Cells
  Route::post('/cells/update', 'CellController@update')->name('cells.update')->middleware('auth');

  //Test
  Route::get('/test', 'TestController@index')->name('test.index');
  Route::get('/test/get', 'TestController@getCells')->name('test.get');
