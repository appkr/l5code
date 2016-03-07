<h1>
  {{ $comment->commentable->title }}
  <small>Written by {{ $comment->user->name }}</small>
</h1>

<hr/>

<p>
  {!! markdown($comment->content) !!}
  <small>
    {{ $comment->created_at->timezone('Asia/Seoul') }}
  </small>
</p>

<hr/>

<footer>
  The email was generated from {{ config('app.url') }}.
</footer>
