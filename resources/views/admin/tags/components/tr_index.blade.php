<tr>
    <td>{{ $value->id }}</td>
    <td>{{ $value->created_at->format('d.m.y H:i:s') }}</td>
    <td><a href="{{ route('tags.edit', $value) }}">{{ $value->name }}</a></td>
    <td>0</td>
    <td>{{ $value->is_active ? 'Активен' : 'Не активнен' }}</td>
    <td>
        <button type="button" class="btn btn-secondary">
            <a href="{{ route('tags.edit', $value) }}" style="color: inherit; text-decoration: none;">Редактировать</a>
        </button>
        <form action="{{ route('tags.destroy', $value) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger"
                    onclick="if(!confirm('Вы уверены, что хотите удалить тэг?')) return false"
                    title="Удалить">Удалить
            </button>
        </form>
    </td>
</tr>
