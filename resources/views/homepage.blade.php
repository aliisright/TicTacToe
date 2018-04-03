@extends('layouts.app')

@section('content')
<div class="container d-flex flex-column align-items-center pt-5">

  <div class="d-flex justify-content-between align-items-center flex-wrap py-5">
      <div>
        @guest
          <a href="{{ Route('login') }}"><button class="btn btn-outline-warning">Connexion</button></a>
          <a href="{{ Route('register') }}"><button class="btn btn-success">Inscription</button></a>
        @else
          <a href="{{ Route('home') }}"><button class="btn btn-outline-primary">Mon compte</button></a>
          <a href="{{ route('logout') }}" onclick="event.preventDefault();
                document.getElementById('logout-form').submit();"><button class="btn btn-danger">Déconnexion</button></a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                  {{ csrf_field() }}
                </form>
        @endguest
        <a href="https://github.com/aliisright/TicTacToe"><button class="btn btn-light"><img class="align-baseline" src="{{asset('img/github-icon.png')}}" width="20px"> Find on GitHub</button></a>
      </div>
  </div>



  <div class="row d-flex justify-content-center align-items-center">
    <div class="col-lg-4">
        <img src="{{asset('img/logo.png')}}" width="400px">
    </div>
    <div class="col-lg-4 offset-4">
      <a class="link" href="{{ route('test.index') }}"><div class="homepage-box">
        <h1 class="py-3">Tester le jeu</h1>
      </div></a>
    </div>
  </div>

</div>
@endsection
