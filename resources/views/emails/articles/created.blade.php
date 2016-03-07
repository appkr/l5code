<h1>
  {{ $article->title }}
  <small>{{ $article->user->name }}</small>
</h1>

<hr/>

<p>
  {{ $article->content }}
  <small>{{ $article->created_at }}</small>

{{--  <br/>
  <br/>

  <div style="text-align: center;">
    <img src="{{ $message->embed(storage_path('elephant.png')) }}" alt="">
  </div>--}}
</p>

<hr/>

<footer>
  본 메일은 {{ config('app.url') }} 에서 보냈습니다.
</footer>
