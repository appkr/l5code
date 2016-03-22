@extends('layouts.app')

@section('content')
  <div class="page-header">
    <h4>
      <a href="{{ route('articles.index') }}">포럼</a>
      <small> / 글 쓰기</small>
    </h4>
  </div>

  <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" class="form__article">
    {!! csrf_field() !!}

    @include('articles.partial.form')

    <div class="form-group text-center">
      <button type="submit" class="btn btn-primary">
        저장하기
      </button>
    </div>
  </form>
@stop