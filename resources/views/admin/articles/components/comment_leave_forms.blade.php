<h5>Оставить комментарий:</h5>
<div style="display: flex; gap: 30px; align-items: flex-end;">
    <form action="{{ route('comments.store') }}" method="POST">
        @csrf
        @include('admin.articles.components.comment_form')
        <br>
        <button class="btn btn-primary" type="submit">Добавить комментарий</button>
    </form>
    <form action="{{ route('comments.store') }}" method="POST">
        @csrf
        @include('admin.articles.components.comment_user_form_fields')
        @include('admin.articles.components.comment_form')
        <br>
        <button class="btn btn-primary" type="submit">Добавить комментарий</button>
    </form>
</div>
