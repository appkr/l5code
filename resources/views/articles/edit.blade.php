@extends('layouts.app')

@section('content')
  <div class="page-header">
    <h4>
      <a href="{{ route('articles.index') }}">포럼</a>
      <small> / 글 수정 / {{ $article->title }}</small>
    </h4>
  </div>

  <form action="{{ route('articles.update', $article->id) }}" method="POST" class="form__article">
    {!! csrf_field() !!}
    {!! method_field('PUT') !!}

    @include('articles.partial.form')

    <div class="form-group text-center">
      <button type="submit" class="btn btn-primary">
        수정하기
      </button>
    </div>
  </form>
@stop
