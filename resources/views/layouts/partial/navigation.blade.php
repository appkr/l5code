<nav class="navbar navbar-default">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
        <span class="sr-only">Toggle Navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="/">
        {{ config('project.name') }}
      </a>
    </div>

    <div class="collapse navbar-collapse" id="app-navbar-collapse">
      <ul class="nav navbar-nav">
        <li>
          <a href="/">
            {{ trans('master.home') }}
          </a>
        </li>
        <li>
          <a href="{{ route('articles.index') }}">
            {{ trans('forum.title') }}
          </a>
        </li>
      </ul>

      <ul class="nav navbar-nav navbar-right">
        @if (auth()->guest())
          <li>
            <a href="{{ route('sessions.create', ['return' => urlencode($currentUrl)]) }}">
              {{ trans('auth.sessions.title') }}
            </a>
          </li>
          <li>
            <a href="{{ route('users.create', ['return' => urlencode($currentUrl)]) }}">
              {{ trans('auth.users.title') }}
            </a>
          </li>
        @else
          <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
              {{ auth()->user()->name }}
              <span class="caret"></span>
            </a>

            <ul class="dropdown-menu" role="menu">
              <li>
                <a href="{{ route('sessions.destroy') }}">
                  <i class="fa fa-btn fa-sign-out"></i>
                  Logout
                </a>
              </li>
            </ul>
          </li>
        @endif
      </ul>
    </div>
  </div>
</nav>