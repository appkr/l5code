@if ($attachments->count())
  <ul class="attachment__article">
    <li><i class="fa fa-paperclip"></i></li>
    @foreach ($attachments as $attachment)
      <li>
        <a href="{{ $attachment->url }}">
          {{ $attachment->filename }} ({{ $attachment->bytes }})
        </a>
      </li>
    @endforeach
  </ul>
@endif