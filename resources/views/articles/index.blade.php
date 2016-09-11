@extends('layouts.app')

@section('content')
  <h1>포럼 글 목록</h1> <hr/>

  <ul>
    @forelse($articles as $article)
      <li>
        <a href="{{ route('articles.show', $article->id) }}">
          {{ $article->title }}
        </a>
        <small>
          by {{ $article->user->name }}
        </small> </li>
    @empty
      <p>글이 없습니다.</p>
    @endforelse
  </ul>

  @if($articles->count())
    <div class="text-center">
      {!! $articles->render() !!}
    </div>
  @endif
@stop