@extends('layouts.app')

@section('content')
<div class="container">

  <div class="d-flex justify-content-between align-items-center flex-wrap py-5">
      <div>
        <img src="{{asset('img/logo.png')}}" width="400px">
      </div>
      <div>
        <a href="{{ route('home') }}"><button class="btn btn-outline-warning">Mon compte</button></a>
        <a href="{{ route('game.index') }}"><button class="btn btn-success">jouer!</button></a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();"><button class="btn btn-danger">Déconnexion</button></a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
      </div>
  </div>

<div>
  <small>PC: mode PC contre l'ordinateur | 2P: mode 2 joueurs</small>
  <a class="badge badge-danger" href="{{ route('history.destroy') }}">Effacer mon historique</a>
</div>
  <div class="row d-flex justify-content-center">
    <div class="col-lg-6 col-md-6 histoy-list">
        <ul class="list-group text-black">
          @foreach($games as $game)
          <li class="list-group-item">
            {{$game->mode == 'PC' ? 'PC' : '2P'}} | Tu as joué contre {{ $game->mode == 'PC' ? 'XO-3000' : $game->against }} et tu as {{ $game->won ? 'gagné' : 'perdu' }}
          </li>
          @endforeach
        </ul>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="homepage-box">
        <h1>{{$gameWins->count()}} gagnés!</h1>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="homepage-box">
        <h1>{{$gameLoses->count()}} ratés :/</h1>
      </div>
    </div>
  </div>
</div>
@endsection
