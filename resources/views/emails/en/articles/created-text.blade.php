New article by {{ $article->user->name }}

---

{{ $article->content }}
Created at {{ $article->created_at->timezone('Asia/Seoul') }}

---

Email sent by {{ config('app.url') }}
