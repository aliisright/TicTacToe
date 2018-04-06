<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Game;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $gameWins = Game::where('won', true)->where('user_id', Auth::id())->get();
        $gameLoses = Game::where('won', false)->where('user_id', Auth::id())->get();

        return view('home', ['gameWins' => $gameWins, 'gameLoses' => $gameLoses]);
    }
}
