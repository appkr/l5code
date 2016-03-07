<h4>Welcome to {{ config('project.name') }}! Please confirm your email address.</h4>

<p>
  To confirm that this address is yours, please click the following link or paste it into your browser:
  <a href="{{ route('users.confirm', $user->confirm_code) }}">
    {{ route('users.confirm', $user->confirm_code) }}
  </a>
</p>

<footer>
  The email was generated from {{ config('app.url') }}.
</footer>
