@extends('layouts.app')

@section('content')
    <h1>
      {{ $article->title }}
    </h1>

    <hr/>

    <article>
      {!! app(ParsedownExtra::class)->text($article->content) !!}
      <small>
        by {{ $article->user->name }}
        {{ $article->created_at->diffForHumans() }}
      </small>
    </article>
  </div>
@stop
