<tr>
    <td>{{ $value->id }}</td>
    <td>{{ $value->created_at->format('d.m.y H:i:s') }}</td>
    <td>@if(isset($value->thumbnail)) @include('admin.categories.components.image_profile', ['item' => $value]) @endif</td>
    <td><a href="{{ route('categories.show', $value) }}">{{ $value->name }}</a></td>
    <td>0</td>
    <td>{{ $value->view }}</td>
    <td>{{ $value->is_active ? 'Активен' : 'Не активен' }}</td>
    <td>
        <button type="button" class="btn btn-secondary">
            <a href="{{ route('categories.edit', $value) }}" style="color: inherit; text-decoration: none;">Редактировать</a>
        </button>
        <form action="{{ route('categories.destroy', $value) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="if(!confirm('Вы уверены, что хотите удалить категорию?')) return false" title="Удалить">Удалить
            </button>
        </form>
    </td>
</tr>
