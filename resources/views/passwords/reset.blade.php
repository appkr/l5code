@extends('layouts.app')

@section('content')
  <form action="{{ route('reset.store') }}" method="POST" role="form" class="form__auth">
    {!! csrf_field() !!}

    <input type="hidden" name="token" value="{{ $token }}">

    <div class="page-header">
      <h4>비밀번호 바꾸기</h4>
      <p class="text-muted">
        회원가입한 이메일을 입력하고 새로운 비밀번호를 입력하세요.
      </p>
    </div>

    <div class="form-group">
      <input type="email" name="email" class="form-control" placeholder="이메일" value="{{ old('email') }}" autofocus>
      {!! $errors->first('email', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <input type="password" name="password" class="form-control" placeholder="새로운 비밀번호">
      {!! $errors->first('password', '<span class="form-error">:message</span>') !!}
    </div>

    <div class="form-group">
      <input type="password" name="password_confirmation" class="form-control" placeholder="비밀번호 확인">
      {!! $errors->first('password_confirmation', '<span class="form-error">:message</span>') !!}
    </div>

    <button class="btn btn-primary btn-lg btn-block" type="submit">
      비밀번호 바꾸기
    </button>
  </form>
@stop