@extends('layouts.app')

@section('content')
  <header class="page-header">
    <h2>{{ trans('docs.title') }}</h2>
  </header>

  <div class="row">
    <aside class="col-md-3 docs__sidebar">
      {!! $index !!}
    </aside>

    <article class="col-md-9 docs__content">
      {!! $content !!}
    </article>
  </div>
@stop