@extends('layouts.app')

@section('content')
  <div class="page-header">
    <h4>
      <a href="{{ route('articles.index') }}">
        {{ trans('forum.title') }}
      </a>
      <small>
        / {{ trans('forum.articles.edit') }}
        / {{ $article->title }}
      </small>
    </h4>
  </div>

  <form action="{{ route('articles.update', $article->id) }}" method="POST" enctype="multipart/form-data" class="form__article">
    {!! csrf_field() !!}
    {!! method_field('PUT') !!}

    @include('articles.partial.form')

    <div class="form-group text-center">
      <button type="submit" class="btn btn-primary">
        {{ trans('forum.articles.update') }}
      </button>
    </div>
  </form>
@stop
