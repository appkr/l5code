<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com/css?family=Raleway:100,600" rel="stylesheet" type="text/css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap-theme.css" rel="stylesheet">

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
      font-size: 84px;
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
  </style>
</head>
<body>
  <div class="flex-center full-height">
    @include('flash::message')

    <div class="content">
      <div class="title m-b-md">
        <a href="route('home')">
          {{ config('app.name', 'Laravel') }}
        </a>
      </div>

      <div class="links">
        <a href="https://laravel.com/docs">Documentation</a>
        <a href="https://laracasts.com">Laracasts</a>
        <a href="https://laravel-news.com">News</a>
        <a href="https://forge.laravel.com">Forge</a>
        <a href="https://github.com/laravel/laravel">GitHub</a>
      </div>
    </div>
  </div>
</body>
</html>
