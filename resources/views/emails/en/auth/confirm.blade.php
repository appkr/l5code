Welcome {{ $user->name }}
Open the following Url to confirm your registration: {{ route('users.confirm', $user->confirm_code) }}