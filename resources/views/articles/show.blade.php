@extends('layouts.app')

@section('content')
  @php $viewName = 'articles.show'; @endphp

  <div class="page-header">
    <h4>
      <a href="{{ route('articles.index') }}">
        {{ trans('forum.title') }}
      </a>
      <small>
        / {{ $article->title }}
      </small>
    </h4>
  </div>

  <div class="row container__article">
    <div class="col-md-3 sidebar__article">
      <aside>
        @include('articles.partial.search')

        @include('tags.partial.index')
      </aside>
    </div>

    <div class="col-md-9 list__article">
      <article data-id="{{ $article->id }}" id="item__article">
        @include('articles.partial.article', compact('article'))

        <div class="content__article">
          {!! markdown($article->content) !!}
        </div>

        @include('tags.partial.list', ['tags' => $article->tags])
      </article>

      <div class="text-center action__article">
        @can('update', $article)
          <a href="{{ route('articles.edit', $article->id) }}" class="btn btn-info">
            <i class="fa fa-pencil"></i>
            {{ trans('forum.articles.edit') }}
          </a>
        @endcan
        @can('delete', $article)
          <button class="btn btn-danger button__delete">
            <i class="fa fa-trash-o"></i>
            {{ trans('forum.articles.destroy') }}
          </button>
        @endcan
        <a href="{{ route('articles.index') }}" class="btn btn-default">
          <i class="fa fa-list"></i>
          {{ trans('forum.articles.index') }}
        </a>
      </div>

      <div class="container__comment">
        @include('comments.index')
      </div>
    </div>
  </div>
@stop

@section('script')
  <script>
    $('.button__delete').on('click', function (e) {
      var articleId = $('article').data('id');

      if (confirm('{{ trans('forum.articles.deleting') }}')) {
        $.ajax({
          type: 'DELETE',
          url: '/articles/' + articleId
        }).then(function () {
          window.location.href = '/articles';
        });
      }
    });
  </script>
@stop