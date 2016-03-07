<h1>
  {{ $article->title }}
  <small>{{ $article->user->name }}</small>
</h1>

<hr/>

<p>
  {!! markdown($article->content) !!}
  <small>{{ $article->created_at->timezone('Asia/Seoul') }}</small>
</p>

<hr/>

<footer>
  The email was generated from {{ config('app.url') }}.
</footer>
