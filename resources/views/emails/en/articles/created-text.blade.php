{{ $article->user->name }} wrote a article.

---

{{ $article->title }}
{{ $article->content }}
{{ $article->created_at->timezone('Asia/Seoul') }}

---

The email was generated from {{ config('app.url') }}.