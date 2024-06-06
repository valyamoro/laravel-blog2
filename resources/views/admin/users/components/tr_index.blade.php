<tr>
    <td>{{ $value->id }}</td>
    <td>{{ $value->created_at->format('d.m.y H:i:s') }}</td>
    <td><a href="{{ route('users.edit', $value) }}">{{ $value->username }}</a></td>
    <td>{{ $value->email }}</td>
    <td>
        <div class="custom-control custom-switch">
            <input id="customSwitch_{{ $value->id }}" type="checkbox" name="is_banned" class="custom-control-input"
                   @if(isset($value) && $value->is_banned === true) checked @endif
                   data-id="{{ $value->id }}" data-item="user" data-status-name="is_banned">
            <label for="customSwitch_{{ $value->id }}" class="custom-control-label"></label>
        </div>
    </td>
    <td>
        <button type="button" class="btn btn-secondary">
            <a href="{{ route('users.edit', $value) }}" style="color: inherit; text-decoration: none;">Редактировать</a>
        </button>
        <form action="{{ route('users.destroy', $value) }}" method="post">
            @csrf
            @method('DELETE')
            <button style="width: 131px" type="submit" class="btn btn-danger" onclick="if(!confirm('Вы уверены, что хотите удалить пользователя?')) return false" title="Удалить">Удалить
            </button>
        </form>
        <button style="width: 131px" type="button" class="btn btn-primary">
            <a href="{{ route('users.show', $value) }}" style="color: inherit; text-decoration: none;">Страница</a>
        </button>
    </td>
</tr>
