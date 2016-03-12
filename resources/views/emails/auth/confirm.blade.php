<h4>{{ $user->name }}님, {{ config('project.name') }}에 오신 것을 환영합니다.</h4>
<p>
  가입확인을 위해 브라우저에서 다음 주소를 열어 주세요:
  <a href="{{ route('users.confirm', $user->confirm_code) }}">
    {{ route('users.confirm', $user->confirm_code) }}
  </a>
</p>
