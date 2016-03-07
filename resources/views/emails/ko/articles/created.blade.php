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
  본 메일은 {{ config('app.url') }} 에서 보냈습니다.
</footer>
