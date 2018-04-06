<?php

namespace App\Http\Controllers;

use App\Game;
use Illuminate\Http\Request;

use Auth;
use App\Cell;

class GameController extends Controller
{
    public function index()
    {
        return view('game.index');
    }

    public function newGame()
    {
        $newGame = Game::create([
          'won' => null,
          'user_id' => Auth::id(),
        ]);

        $cells =[];
        for($i=1; $i<=9; $i++) {
          $cell = Cell::create([
            'selected' => false,
            'sign' => '',
            'num' => $i,
            'game_id' => $newGame->id,
          ]);
          $cells[] = $cell;
        }
        return Self::apiGetGame($newGame->id);
    }

    public function apiGetGame($id)
    {
        $game = Game::where('id', $id)->firstOrFail();
        $cells = $game->cells()->get();
        if($game->user->id == Auth::id()) {
          $connected = true;
          $user = Auth::user();
        } else {
          $connected = false;
          $user = null;
        }

        return ['user' => $user, 'connected' => $connected, 'game' => $game, 'cells' => $cells];
    }

    public function setWinner(Request $request)
    {
        $game = Game::find($request->input('id'));
        $game->won = $request->input('won');
        $game->against = $request->input('against');
        $game->mode = $request->input('mode');
        $game->update();
    }

    //Historique
    public function getHistory()
    {
        $games = Auth::user()->games()->orderBy('created_at', 'desc')->get();

        $gameWins = Game::where('won', true)->where('user_id', Auth::id())->get();
        $gameLoses = Game::where('won', false)->where('user_id', Auth::id())->get();

        return view('history', ['games' => $games, 'gameWins' => $gameWins, 'gameLoses' => $gameLoses]);
    }

    public function destroyHistory()
    {
        $games = Auth::user()->games()->get();
        foreach ($games as $game) {
          $game->delete();
        }

        return redirect()->back();
    }

}
