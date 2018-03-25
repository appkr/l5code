@extends('layouts.app')

@section('content')
  <form action="{{ route('users.store') }}" method="POST" role="form" class="form__auth">
    {!! csrf_field() !!}

    @if ($return = request('return'))
      <input type="hidden" name="return" value="{{ $return }}">
    @endif

    <div class="page-header">
      <h4>
        {{ trans('auth.users.title') }}
      </h4>
      <p class="text-muted">
        {{ trans('auth.users.description') }}
      </p>
    </div>

    <div class="form-group">
      <a class="btn btn-default btn-lg btn-block" href="{{ route('social.login', ['github']) }}">
        <strong>
          <i class="fa fa-github"></i>
          {{ trans('auth.sessions.login_with_github') }}
        </strong>
      </a>
    </div>

    <div class="login-or">
      <hr class="hr-or">
      <span class="span-or">or</span>
    </div>

    <div class="form-group {{ $errors->has('name') ? 'has-error' : '' }}">
      <input type="text" name="name" class="form-control" placeholder="{{ trans('auth.form.name') }}" value="{{ old('name') }}" autofocus/>
      {!! $errors->first('name', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
      <input type="email" name="email" class="form-control" placeholder="{{ trans('auth.form.email') }}" value="{{ old('email') }}"/>
      {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
      <input type="password" name="password" class="form-control" placeholder="{{ trans('auth.form.password') }}"/>
      {!! $errors->first('password', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
      <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('auth.form.password_confirmation') }}" />
      {!! $errors->first('password_confirmation', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group" style="margin-top: 2em;">
      <button class="btn btn-primary btn-lg btn-block" type="submit">
        {{ trans('auth.users.send_registration') }}
      </button>
    </div>
  </form>
@stop

