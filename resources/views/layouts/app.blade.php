<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
  <meta name="msapplication-tap-highlight" content="no">

  <meta name="description" content="{{ config('project.description') }}">

  <!-- Facebook Meta -->
  <meta property="og:title" content="{{ config('project.name') }}">
  <meta property="og:image" content="">
  <meta property="og:type" content="Website">
  <meta property="og:author" content="">

  <!-- Google Meta -->
  <meta itemprop="name" content="">
  <meta itemprop="description" content="{{ config('project.description') }}">
  <meta itemprop="image" content="">
  <meta itemprop="author" content=""/>

  <!-- Twitter Meta-->
  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="">
  <meta name="twitter:title" content="{{ config('project.name') }}">
  <meta name="twitter:description" content="{{ config('project.description') }}">
  <meta name="twitter:image" content="">
  <meta name="twitter:domain" content="{{ config('project.url') }}">

  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('project.name') }}</title>

  <link href="https://fonts.googleapis.com/css?family=Lato:100,300,400,700" rel="stylesheet">
  <link href="https://fonts.googleapis.com/earlyaccess/nanumgothic.css" rel="stylesheet">
  <link href="{{ elixir("css/app.css") }}" rel="stylesheet">
  @yield('style')

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!--[if lt IE 9]>
  <script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
  <script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>

<body id="app-layout">
  @include('layouts.partial.navigation')

  <div class="container">
    @include('flash::message')

    @yield('content')
  </div>

  @include('layouts.partial.footer')

  <script src="{{ elixir("js/app.js") }}"></script>
  @yield('script')
</body>

</html>