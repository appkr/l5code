<h1>
  {{ $article->title }}
  <small>
    by {{ $article->user->name }}
  </small>
</h1>

<hr/>

<p>
  {{ $article->content }}
  <small>
    {{ $article->created_at->timezone('Asia/Seoul') }}
  </small>
</p>

<hr/>

<footer>
  Email sent by {{ config('app.url') }}
</footer>