<p>{{ $comment->adminUser->username }}</p>
<p>{{ $comment->comment }}</p>
@include('admin.articles.components.comment_reply_form')
<div style="margin-left: 20px">
    @foreach($comment->children()->get()->where('is_active', '=', 1) as $child)
        @include('admin.articles.components.comment_item', ['comment' => $child])
    @endforeach
</div>
