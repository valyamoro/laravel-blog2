<tr>
    <td>{{ $value->id }}</td>
    <td>{{ $value->created_at->format('d.m.y H:i:s') }}</td>
    <td>@if(isset($value->thumbnail)) @include('admin.categories.components.image_profile', ['item' => $value]) @endif</td>
    <td><a href="{{ route('categories.show', $value) }}">{{ $value->name }}</a></td>
    <td>0</td>
    <td>{{ $value->view }}</td>
    <td>
        <div class="custom-control custom-switch">
            <input id="customSwitch_{{ $value->id }}" type="checkbox" name="is_active" class="custom-control-input"
                   @if(isset($value) && $value->is_active === true) checked @endif
                   onchange="updateActiveStatus({{ $value->id }}, 'category', 'is_active', this.checked)">
            <label for="customSwitch_{{ $value->id }}" class="custom-control-label"></label>
        </div>
    </td>
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
