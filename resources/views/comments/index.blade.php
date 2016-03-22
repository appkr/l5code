<div class="page-header">
  <h4>댓글</h4>
</div>

<div class="form__new__comment">
  @if($currentUser)
    @include('comments.partial.create')
  @else
    @include('comments.partial.login')
  @endif
</div>

<div class="list__comment">
  @forelse($comments as $comment)
    @include('comments.partial.comment', [
      'parentId' => $comment->id,
      'isReply' => false,
      'hasChild' => $comment->replies->count(),
      'isTrashed' => $comment->trashed(),
    ])
  @empty
  @endforelse
</div>

@section('script')
  @parent
  <script>
    // Send delete a comment request to the server
    $('.btn__delete__comment').on('click', function(e) {
      var commentId = $(this).closest('.item__comment').data('id'),
        articleId = $('#item__article').data('id');

      if (confirm('댓글을 삭제합니다.')) {
        $.ajax({
          type: 'POST',
          url: "/comments/" + commentId,
          data: {
            _method: "DELETE"
          }
        }).success(function() {
          window.location.href = '/articles/' + articleId;
        });
      }
    });

    // Toggle visibility of the reply form
    $('.btn__reply__comment').on('click', function(e) {
      var el__create = $(this).closest('.item__comment').find('.media__create__comment').first(),
        el__edit = $(this).closest('.item__comment').find('.media__edit__comment').first();

      el__edit.hide('fast');
      el__create.toggle('fast').end().find('textarea').focus();
    });

    // Toggle visibility of the edit form
    $('.btn__edit__comment').on('click', function(e) {
      var el__create = $(this).closest('.item__comment').find('.media__create__comment').first(),
        el__edit = $(this).closest('.item__comment').find('.media__edit__comment').first();

      el__create.hide('fast');
      el__edit.toggle('fast').end().find('textarea').first().focus();
    });

    // Send save a vote request to the server
    $('.btn__vote__comment').on('click', function(e) {
      var self = $(this),
        commentId = self.closest('.item__comment').data('id');

      $.ajax({
        type: 'POST',
        url: '/comments/' + commentId + '/votes',
        data: {
          vote: self.data('vote')
        }
      }).success(function(data) {
        self.find('span').html(data.value).fadeIn();
        self.attr('disabled', 'disabled');
        self.siblings().attr('disabled', 'disabled');
      }).error(function() {
        //
      });
    });
  </script>
@endsection