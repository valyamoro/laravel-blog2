<form action="{{ route('comments.store') }}" method="POST">
    @csrf
    <div>
        <div class="replyFormContainer">
            <textarea name="comment" placeholder="Ответить на этот комментарий" style="display: none;" required></textarea>
            <input type="hidden" name="article_id" value="{{ $comment->article()->get()->first()->id }}">
            <input type="hidden" name="parent_id" value="{{ $comment->id }}">
            <button type="button" class="showReplyForm btn btn-primary">Ответить</button>
            <button type="submit" class="submitReplyForm btn btn-primary" style="display: none;">Отправить ответ</button>
            <button type="button" class="cancelReplyForm btn btn-primary" style="display: none;">Отмена</button>
        </div>
        <br>
    </div>
</form>
<script>
    document.addEventListener('click', function (event) {
        let container = event.target.closest('.replyFormContainer');
        if (!container) return;

        let replyTextarea = container.querySelector('textarea');
        let showReplyFormBtn = container.querySelector('.showReplyForm');
        let submitReplyFormBtn = container.querySelector('.submitReplyForm');
        let cancelReplyFormBtn = container.querySelector('.cancelReplyForm');

        if (event.target.classList.contains('showReplyForm')) {
            replyTextarea.style.display = 'block';
            submitReplyFormBtn.style.display = 'inline-block';
            cancelReplyFormBtn.style.display = 'inline-block';
            showReplyFormBtn.style.display = 'none';
        }

        if (event.target.classList.contains('cancelReplyForm')) {
            replyTextarea.style.display = 'none';
            submitReplyFormBtn.style.display = 'none';
            cancelReplyFormBtn.style.display = 'none';
            showReplyFormBtn.style.display = 'inline-block';
        }
    });
</script>
