<tr>
    <td>{{ $value->id }}</td>
    <td>{{ $value->created_at->format('d.m.y H:i:s') }}</td>
    <td><a href="{{ route('comments.show', $value) }}">{{ Str::limit($value->comment, 10) }}</a></td>
    <td><a href="{{ route('articles.show', $value->article->id) }}">{{ $value->article->title }}</a></td>
    <td><a href="{{ route('admin-users.show', $value->adminUser->id) }}">{{ $value->adminUser->username }}</a></td>
    <td>
        <div class="custom-control custom-switch">
            <input id="customSwitch_{{ $value->id }}" type="checkbox" name="is_active" class="custom-control-input"
                   @if(isset($value) && $value->is_active === true) checked @endif
                   onchange="updateActiveStatus({{ $value->id }}, 'comment', 'is_active', this.checked)">
            <label for="customSwitch_{{ $value->id }}" class="custom-control-label"></label>
        </div>
    </td>
    <td>
        <button type="button" class="btn btn-secondary">
            <a href="{{ route('comments.show', $value) }}"
               style="color: inherit; text-decoration: none;">Редактировать</a>
        </button>
        <form action="{{ route('comments.destroy', $value) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger"
                    onclick="if(!confirm('Вы уверены, что хотите удалить комментарий?')) return false" title="Удалить">
                Удалить
            </button>
        </form>
    </td>
</tr>
