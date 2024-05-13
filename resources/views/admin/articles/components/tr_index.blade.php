<tr>
    <td>{{ $value->id }}</td>
    <td>{{ $value->created_at->format('d.m.y H:i:s') }}</td>
    <td>@if(isset($value->thumbnail)) @include('admin.categories.components.image_profile', ['item' => $value]) @endif</td>
    <td><a href="{{ route('articles.show', $value) }}">{{ $value->title }}</a></td>
    <td>0</td>
    <td>{{ $value->is_active ? 'Активен' : 'Не активен' }}</td>
    <td><a href="{{ route('articles.show', $value) }}">{{ $value->name }}</a>
        <button type="button" class="btn btn-secondary">
            <a href="{{ route('articles.edit', $value) }}"
               style="color: inherit; text-decoration: none;">Редактировать</a>
        </button>
        <form action="{{ route('articles.destroy', $value) }}" method="post">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="if(!confirm('Вы уверены, что хотите удалить статью?')) return false" title="Удалить">Удалить
            </button>
        </form>
    </td>
</tr>
