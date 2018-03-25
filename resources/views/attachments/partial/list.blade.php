@if ($attachments->count())
  <ul class="attachments__article">
    <li>
      <i class="fa fa-paperclip"></i>
    </li>
    @foreach ($attachments as $attachment)
      <li>
        <a href="{{ $attachment->url }}">
          {{ $attachment->filename }} ({{ $attachment->bytes }})
        </a>
      </li>
    @endforeach
  </ul>
@endif