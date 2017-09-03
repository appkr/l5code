<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">

  <!-- Styles -->
  <style>
    html, body {
      background-color: #fff;
      color: #636b6f;
      font-family: 'Raleway', sans-serif;
      font-weight: 100;
      height: 100vh;
      margin: 0;
    }

    .full-height {
      height: 100vh;
    }

    .flex-center {
      align-items: center;
      display: flex;
      justify-content: center;
    }

    .position-ref {
      position: relative;
    }

    .top-right {
      position: absolute;
      right: 10px;
      top: 18px;
    }

    .content {
      text-align: center;
    }

    .title {
      font-size: 64px;
    }

    .title a,
    .links > a
    {
      color: #636b6f;
      text-decoration: none;
    }

    .links > a {
      padding: 0 25px;
      font-size: 12px;
      font-weight: 600;
      letter-spacing: .1rem;
      text-transform: uppercase;
    }

    .m-b-md {
      margin-bottom: 30px;
    }

    .alert {
       display: inline-block;
       position: fixed;
       bottom: 50px;
       right: 15px;
       max-width: 450px;
       opacity: .8;
       z-index: 999;
    }
  </style>
</head>
<body>
  <div class="flex-center full-height">
    @include('flash::message')

    <div class="content">
      <div class="title m-b-md">
        <a href="{{ route('home') }}">
          {{ config('app.name', 'Laravel') }}
        </a>
      </div>

      <div class="links">
        <a href="{{ url('docs') }}">
          {{ trans('docs.title') }}
        </a>
        <a href="{{ route('articles.index') }}">
          {{ trans('forum.title') }}
        </a>
        @if (auth()->guest())
          <a href="{{ route('sessions.create') }}">
            {{ trans('auth.sessions.title') }}
          </a>
          <a href="{{ route('users.create') }}">
            {{ trans('auth.users.title') }}
          </a>
        @else
          <a href="{{ route('sessions.destroy') }}">
            {{ auth()->user()->name }} • {{ trans('auth.sessions.destroy') }}
          </a>
        @endif
      </div>
    </div>
  </div>
</body>
</html>
