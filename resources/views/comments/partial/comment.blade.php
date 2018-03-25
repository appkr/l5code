@php
  $voted = null;

  if ($currentUser) {
    $voted = $comment->votes->contains('user_id', $currentUser->id)
      ? 'disabled="disabled"' : null;
  }
@endphp

@if ($isTrashed and ! $hasChild)
  <!-- // 1. 삭제된 댓글이면서 자식 댓글도 없다. 이때는 아무것도 출력할 필요가 없다. -->
@elseif ($isTrashed and $hasChild)
  <!-- // 2. 삭제된 댓글이지만 자식 댓글이 있다. '삭제되었습니다'라고 알리고 자식 댓글은 계속 출력한다. -->
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

      <div class="text-danger content__comment">
        {{ trans('forum.comments.deleted') }}
      </div>

      <div class="action__comment">
        @if ($currentUser)
          <button class="btn__vote__comment" data-vote="up" title="{{ trans('forum.comments.like') }}" {{ $voted }}>
            <i class="fa fa-chevron-up"></i>
            <span>{{ $comment->up_count }}</span>
          </button>

          <span> | </span>

          <button class="btn__vote__comment" data-vote="down" title="{{ trans('forum.comments.dislike') }}" {{ $voted }}>
            <i class="fa fa-chevron-down"></i> <span>{{ $comment->down_count }}</span>
          </button>
          •
        @endif

        @can('update', $comment)
          <button class="btn__delete__comment">{{ trans('forum.comments.destroy') }}</button> •
          <button class="btn__edit__comment">{{ trans('forum.comments.edit') }}</button> •
        @endcan

        @if ($currentUser)
          <button class="btn__reply__comment">
            {{ trans('forum.comments.reply') }}
          </button>
        @endif
      </div>

      @if($currentUser)
        @include('comments.partial.create', ['parentId' => $comment->id])
      @endif

      @forelse ($comment->replies as $reply)
        @include('comments.partial.comment', [
          'comment' => $reply,
          'isReply' => true,
          'hasChild' => $reply->replies->count(),
          'isTrashed' => $reply->trashed(),
        ])
      @empty
      @endforelse
    </div>
  </div>
@else
  <!-- // 3. 살아 있는 댓글이다. 자신을 출력하고, 자식 댓글도 계속 출력한다. -->
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
          <button class="btn__vote__comment" data-vote="up" title="{{ trans('forum.comments.like') }}" {{ $voted }}>
            <i class="fa fa-chevron-up"></i>
            <span>{{ $comment->up_count }}</span>
          </button>

          <span> | </span>

          <button class="btn__vote__comment" data-vote="down" title="{{ trans('forum.comments.dislike') }}" {{ $voted }}>
            <i class="fa fa-chevron-down"></i> <span>{{ $comment->down_count }}</span>
          </button>
          •
        @endif

        @can('update', $comment)
          <button class="btn__delete__comment">{{ trans('forum.comments.destroy') }}</button> •
          <button class="btn__edit__comment">{{ trans('forum.comments.edit') }}</button> •
        @endcan

        @if ($currentUser)
          <button class="btn__reply__comment">
            {{ trans('forum.comments.reply') }}
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
          'hasChild' => $reply->replies->count(),
          'isTrashed' => $reply->trashed(),
        ])
      @empty
      @endforelse
    </div>
  </div>
@endif