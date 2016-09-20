<div class="media">
  @include('users.partial.avatar', ['user' => $article->user])

  <div class="media-body">
    <h4 class="media-heading">
      <a href="{{ route('articles.show', $article->id) }}">
        {{ $article->title }}
      </a>
    </h4>

    <p class="text-muted meta__article">
      By
      <a href="{{ gravatar_profile_url($article->user->email) }}">
        {{ $article->user->name }}
      </a>

      <small>
        • {{ $article->created_at->diffForHumans() }}
      </small>
    </p>

    @if ($viewName === 'articles.index')
      @include('tags.partial.list', ['tags' => $article->tags])
    @endif

    @if ($viewName === 'articles.show')
      @include('attachments.partial.list', ['attachments' => $article->attachments])
    @endif
  </div>
</div>
