@extends('layouts.app')

@section('content')
<div class="container">

  <div class="logo py-5">
      <div class="alis-logo">
        <img src="img/alis-icon.png" width="100px">
      </div>
      <div class="tictactoe-logo">
        <img src="img/tictactoe-icon.png" width="300px">
      </div>
  </div>

  <div>
    <h1 class="py-3">Bonjour {{Auth::user()->name}}!</h1>
  </div>

  <div class="row d-flex justify-content-center">
    <div class="col-md-3 homepage-box">
      <h1>{{$gameWins->count()}} gagnés</h1>
    </div>
    <div class="col-md-3 homepage-box">
      <h1>{{$gameLoses->count()}} ratés :/</h1>
    </div>
    <div class="col-md-3 homepage-box">
      <a href="{{ Route('game.index') }}"><button class="btn btn-success"><h1>Jouer!</h1></button></a>
    </div>
    <div class="col-md-3 homepage-box">
      <a href="{{ Route('history.index') }}"><h4>Mon historique de jeux</h4></a>
    </div>
  </div>
</div>
@endsection
