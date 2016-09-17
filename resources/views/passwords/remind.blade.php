@extends('layouts.app')

@section('content')
  <form action="{{ route('remind.store') }}" method="POST" role="form" class="form__auth">
    {!! csrf_field() !!}

    <div class="page-header">
      <h4>
        {{ trans('auth.passwords.title_reminder') }}
      </h4>
      <p class="text-muted">
        {{ trans('auth.passwords.desc_reminder') }}
      </p>
    </div>

    <div class="form-group">
      <input type="email" name="email" class="form-control" placeholder="{{ trans('auth.form.email') }}" value="{{ old('email') }}" autofocus>
      {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    </div>

    <button class="btn btn-primary btn-lg btn-block" type="submit">
      {{ trans('auth.passwords.send_reminder') }}
    </button>
  </form>
@stop