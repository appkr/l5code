@php
  $voted = null;

  if ($currentUser) {
    $voted = $comment->votes->contains('user_id', $currentUser->id)
      ? 'disabled="disabled"' : null;
  }
@endphp

<div class="media item__comment {{ $isReply ? 'sub' : 'top' }}" data-id= "{{ $comment->id }}" id="comment_{{ $comment->id }}">
  @include('users.partial.avatar', ['user' => $comment->user, 'size' => 32])

  <div class="media-body">
    <h5 class="media-heading">
      <a href="{{ gravatar_profile_url($comment->user->email) }}">
        {{ $comment->user->name }}
      </a>
      <small>
        {{ $comment->created_at->diffForHumans() }}
      </small>
    </h5>

    <div class="content__comment">
      {!! markdown($comment->content) !!}
    </div>

    <div class="action__comment">
      @if ($currentUser)
        <button class="btn__vote__comment" data-vote="up" title="좋아요" {{ $voted }}>
          <i class="fa fa-chevron-up"></i>
          <span>{{ $comment->up_count }}</span>
        </button>

        <span> | </span>

        <button class="btn__vote__comment" data-vote="down" title="싫어요" {{ $voted }}>
          <i class="fa fa-chevron-down"></i> <span>{{ $comment->down_count }}</span>
        </button>
        •
      @endif

      @can('update', $comment)
        <button class="btn__delete__comment">댓글 삭제</button> •
        <button class="btn__edit__comment">댓글 수정</button> •
      @endcan

      @if ($currentUser)
        <button class="btn__reply__comment">
          답글 쓰기
        </button>
      @endif
    </div>


    @if($currentUser)
      @include('comments.partial.create', ['parentId' => $comment->id])
    @endif

    @can('update', $comment)
      @include('comments.partial.edit')
    @endcan

    @forelse ($comment->replies as $reply)
      @include('comments.partial.comment', [
        'comment' => $reply,
        'isReply' => true,
      ])
    @empty
    @endforelse
  </div>
</div>