<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="msapplication-tap-highlight" content="no">

  <!-- SEO -->
  <meta name="description" content="{{ config('project.description') }}">

  <!-- Facebook Meta -->
  <meta property="og:title" content="{{ config('app.name') }}">
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
  <meta name="twitter:title" content="{{ config('app.name') }}">
  <meta name="twitter:description" content="{{ config('project.description') }}">
  <meta name="twitter:image" content="">
  <meta name="twitter:domain" content="{{ config('project.url') }}">

  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ config('app.name', 'Laravel') }}</title>

  <!-- Styles -->
  <link href="{{ elixir('css/app.css') }}" rel="stylesheet">

  @yield('style')

  <!-- Scripts -->
  <script>
    window.Laravel = <?php echo json_encode([
      'csrfToken' => csrf_token(),
      'currentUser' => $currentUser,
      'currentRouteName' => $currentRouteName,
      'currentLocale' => $currentLocale,
      'currentUrl' => $currentUrl,
    ]); ?>
  </script>
</head>

<body id="app-layout">
  @include('layouts.partial.navigation')

  <div class="container">
    @include('flash::message')

    @yield('content')
  </div>

  @include('layouts.partial.footer')

  <!-- Scripts -->
  <script src="{{ elixir('js/app.js') }}"></script>

  @yield('script')
</body>
</html>
