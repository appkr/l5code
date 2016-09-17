@extends('layouts.app')

@section('content')
  <form action="{{ route('reset.store') }}" method="POST" role="form" class="form__auth">
    {!! csrf_field() !!}

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="page-header">
      <h4>{{ trans('auth.passwords.title_reset') }}</h4>
      <p class="text-muted">
        {{ trans('auth.passwords.desc_reset') }}
      </p>
    </div>

    <div class="form-group">
      <input type="email" name="email" class="form-control" placeholder="{{ trans('auth.form.email') }}" value="{{ old('email') }}" autofocus>
      {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <input type="password" name="password" class="form-control" placeholder="{{ trans('auth.form.password_new') }}">
      {!! $errors->first('password', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <input type="password" name="password_confirmation" class="form-control" placeholder="{{ trans('auth.form.password_confirmation') }}">
      {!! $errors->first('password_confirmation', '<span class="form-error">:message</span>') !!}
    </div>

    <button class="btn btn-primary btn-lg btn-block" type="submit">
      {{ trans('auth.passwords.title_reset') }}
    </button>
  </form>
@stop