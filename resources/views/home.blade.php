@extends('layouts.app')

@section('content')
<div class="container">

  <div class="d-flex align-items-center justify-content-around">
    <h1 class="text-white">Bonjour {{Auth::user()->name}}!</h1>
    <a href="{{ Route('game.index') }}"><button class="btn btn-success">Jouer!</button></a>
  </div>


    <ul class="list-group">
        <li class="list-group-item list-group-item-warning"></li>
    </ul>
</div>
@endsection
