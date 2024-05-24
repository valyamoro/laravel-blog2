<tr>
    <td>{{ $value->id }}</td>
    <td>{{ $value->created_at->format('d.m.y H:i:s') }}</td>
    <td><a href="{{ route('users.show', $value) }}">{{ $value->username }}</a></td>
    <td>{{ $value->email }}</td>
    <td>{{ $value->is_banned ? 'Забанен' : 'Не забанен' }}</td>
    <td>
        <button type="button" class="btn btn-secondary">
            <a href="{{ route('users.edit', $value) }}" style="color: inherit; text-decoration: none;">Редактировать</a>
        </button>
        <form action="{{ route('users.destroy', $value) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger"
                    onclick="if(!confirm('Вы уверены, что хотите удалить пользователя?')) return false"
                    title="Удалить">Удалить
            </button>
        </form>
    </td>
</tr>
