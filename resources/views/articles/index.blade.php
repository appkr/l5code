@extends('layouts.app')

@section('content')
  @php $viewName = 'articles.index'; @endphp

  <div class="page-header">
    <h4>
      <a href="{{ route('articles.index') }}">
        {{ trans('forum.title') }}
      </a>
      <small>
        / {{ trans('forum.articles.index') }}
      </small>
    </h4>
  </div>

  <div class="text-right action__article">
    <a href="{{ route('articles.create') }}" class="btn btn-primary">
      <i class="fa fa-plus-circle"></i>
      {{ trans('forum.articles.create') }}
    </a>

    <!--정렬 UI-->
    <div class="btn-group sort__article">
      <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-sort"></i>
        {{ trans('forum.articles.sort') }}
        <span class="caret"></span>
      </button>

      <ul class="dropdown-menu" role="menu">
        @foreach(config('project.sorting') as $column => $text)
          <li {!! request()->input('sort') == $column ? 'class="active"' : '' !!}>
            {!! link_for_sort($column, $text) !!}
          </li>
        @endforeach
      </ul>
    </div>
  </div>

  <div class="row container__article">
    <div class="col-md-3 sidebar__article">
      <aside>
        @include('articles.partial.search')

        @include('tags.partial.index')
      </aside>
    </div>

    <div class="col-md-9 list__article">
      <article>
        @forelse($articles as $article)
          @include('articles.partial.article', compact('article'))
        @empty
          <p class="text-center text-danger">
            {{ trans('forum.articles.empty') }}
          </p>
        @endforelse
      </article>

      @if($articles->count())
        <div class="text-center paginator__article">
          {!! $articles->appends(request()->except('page'))->render() !!}
        </div>
      @endif
    </div>
  </div>
@stop