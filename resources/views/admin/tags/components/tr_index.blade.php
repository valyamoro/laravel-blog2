<tr>
    <td>{{ $value->id }}</td>
    <td>{{ $value->created_at->format('d.m.y H:i:s') }}</td>
    <td><a href="{{ route('tags.edit', $value) }}">{{ $value->name }}</a></td>
    <td>{{ $value->articles()->get()->count() }}</td>
    <td>
        <div class="custom-control custom-switch">
            <input id="customSwitch_{{ $value->id }}" type="checkbox" name="is_active" class="custom-control-input"
                   @if(isset($value) && $value->is_active === true) checked @endif
                   onchange="updateActiveStatus({{ $value->id }}, 'tag', 'is_active', this.checked)">
            <label for="customSwitch_{{ $value->id }}" class="custom-control-label"></label>
        </div>
    </td>
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
