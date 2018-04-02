@extends('layouts.app')

@section('content')
<div class="container">

  <div class="d-flex justify-content-between align-items-center flex-wrap py-5">
      <div>
        <img src="{{asset('img/logo.png')}}" width="400px">
      </div>
      <div>
        <a href="{{ Route('history') }}"><button class="btn btn-outline-warning">Mon historique</button></a>
        <a href="{{ route('logout') }}" onclick="event.preventDefault();
              document.getElementById('logout-form').submit();"><button class="btn btn-danger">Déconnexion</button></a>
              <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                {{ csrf_field() }}
              </form>
      </div>
  </div>



  <div class="row d-flex justify-content-center">
    <div class="col-lg-3 col-md-6">
      <div class="homepage-box winner">
        <h1 class="py-3">Bonjour {{Auth::user()->name}}!</h1>
      </div>
    </div>
    <div class="col-lg-3 col-md-6">
      <div class="homepage-box">
        <a href="{{ Route('game.index') }}"><button class="btn btn-success"><h1>Jouer!</h1></button></a>
      </div>
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
