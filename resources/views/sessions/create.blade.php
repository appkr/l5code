@extends('layouts.app')

@section('content')
  <form action="{{ route('sessions.store') }}" method="POST" role="form" class="form__auth">
    {!! csrf_field() !!}

    @if ($return = request('return'))
      <input type="hidden" name="return" value="{{ $return }}">
    @endif

    <div class="page-header">
      <h4>
        {{ trans('auth.sessions.title') }}
      </h4>
      <p class="text-muted">
        {{ trans('auth.sessions.description') }}
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

    <div class="form-group {{ $errors->has('email') ? 'has-error' : '' }}">
      <input type="email" name="email" class="form-control" placeholder="{{ trans('auth.form.email') }}" value="{{ old('email') }}" autofocus/>
      {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group {{ $errors->has('password') ? 'has-error' : '' }}">
      <input type="password" name="password" class="form-control" placeholder="{{ trans('auth.form.password') }}">
      {!! $errors->first('password', '<span class="form-error">:message</span>')!!}
    </div>

    <div class="form-group">
      <div class="checkbox">
        <label>
          <input type="checkbox" name="remember" value="{{ old('remember', 1) }}" checked>
          {{ trans('auth.sessions.remember') }}
          <span class="text-danger">
            {{ trans('auth.sessions.remember_help') }}
          </span>
        </label>
      </div>
    </div>

    <div class="form-group">
      <button class="btn btn-primary btn-lg btn-block" type="submit">
        {{ trans('auth.sessions.title') }}
      </button>
    </div>

    <div>
      <p class="text-center">
        {!! trans('auth.sessions.ask_registration', ['url' => route('users.create')]) !!}
      </p>
      <p class="text-center">
        {!! trans('auth.sessions.ask_forgot', ['url' => route('remind.create')]) !!}
      </p>
      <p class="text-center">
        <small class="help-block">
          {{  trans('auth.sessions.caveat_for_social') }}
        </small>
      </p>
    </div>
  </form>
@stop