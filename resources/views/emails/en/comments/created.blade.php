<h1>
  {{ $comment->commentable->title }}
  <small>
    by {{ $comment->user->name }}
  </small>
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
  본 메일은 {{ config('app.url') }} 에서 보냈습니다.
</footer>