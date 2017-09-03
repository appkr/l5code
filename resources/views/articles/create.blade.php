@extends('layouts.app')

@section('content')
  <div class="page-header">
    <h4>
      <a href="{{ route('articles.index') }}">
        {{ trans('forum.title') }}
      </a>
      <small>
        / {{ trans('forum.articles.create') }}
      </small>
    </h4>
  </div>

  <form action="{{ route('articles.store') }}" method="POST" enctype="multipart/form-data" class="form__article">
    {!! csrf_field() !!}

    @include('articles.partial.form')

    <div class="form-group text-center">
      <button type="submit" class="btn btn-primary">
        {{ trans('forum.articles.store') }}
      </button>
    </div>
  </form>
@stop