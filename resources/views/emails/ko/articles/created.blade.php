<h1>
  {{ $article->title }}
  <small>
    {{ $article->user->name }}
  </small>
</h1>

<hr/>

<p>
  {{ $article->content }}
  <small>
    {{ $article->created_at->timezone('Asia/Seoul') }}
  </small>
  <br/>
  <br/>
  @if (File::exists(storage_path('elephant.png')))
    <div style="text-align: center;">
      <img src="{{ $message->embed(storage_path('elephant.png')) }}" alt="">
    </div>
  @endif
</p>

<hr/>

<footer>
  이 메일은 {{ config('app.url') }}에서 보냈습니다.
</footer>