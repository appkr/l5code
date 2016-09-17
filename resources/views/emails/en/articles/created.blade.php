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
  Email sent by {{ config('app.url') }}
</footer>