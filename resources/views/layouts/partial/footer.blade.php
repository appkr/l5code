<footer class="container footer__master">
  <ul class="list-inline pull-right">
    <li><i class="fa fa-language"></i></li>
    <li class="active">한국어</li>
    <li>English</li>
  </ul>

  <div>
    &copy; {{ date('Y') }}
    <a href="{{ config('project.url') }}">
      {{ config('app.name') }}
    </a>
  </div>
</footer>