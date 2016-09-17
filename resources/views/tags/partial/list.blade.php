@if ($tags->count())
  <ul class="tags__article">
    <li>
      <i class="fa fa-tags"></i>
    </li>
    @foreach ($tags as $tag)
      <li>
        <a href="{{ route('tags.articles.index', $tag->slug) }}">
          {{ $tag->{$currentLocale} }}
        </a>
      </li>
    @endforeach
  </ul>
@endif