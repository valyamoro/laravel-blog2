@foreach($item->comments()->where('parent_id', '=', 0)->where('is_active', '=', 1)->orderByDesc('id')->get() as $comment)
    @include('admin.articles.components.comment_item', ['comment' => $comment])
@endforeach
