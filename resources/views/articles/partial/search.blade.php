<form method="get" action="{{ route('articles.index') }}" role="search">
  <input type="text" name="q" class="form-control" placeholder="{{ trans('forum.search') }}"/>
</form>