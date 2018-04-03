@extends('layouts.app')

@section('content')
  <ul class="list-group col-md-6">
    @foreach($games as $game)
    <a><li class="list-group-item">{{$game->id}}</li></a>
    @endforeach
  </ul>
@endsection
