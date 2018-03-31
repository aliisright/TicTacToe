<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Bootstrap4 -->
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" rel="stylesheet" type="text/css">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>
        @if (!\Request::is('game'))
          <div class="row">
            <nav class="col-md-3">
              <ul>
                  @guest
                    <a href="{{Route('login')}}"><li>Connexion</li></a>
                    <a href="{{Route('register')}}"><li>Inscription</li></a>
                  @else
                    <a href="{{Route('game.index')}}"><li>Jouer</li></a>
                    <a href="{{Route('home')}}"><li>Mon profile</li></a>
                  @endguest
              </ul>
            </nav>

            <main class="col-md-9">
                @yield('content')
            </main>
          </div>
        @else
          <main>
              @yield('content')
          </main>
        @endif
</body>
</html>
