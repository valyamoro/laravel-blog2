<tr>
    <td>{{ $value->id }}</td>
    <td>{{ $value->created_at->format('d.m.y H:i:s') }}</td>
    <td>@if(isset($value->thumbnail))
            @include('admin.articles.components.image_profile', ['item' => $value])
        @endif</td>
    <td><a href="{{ route('articles.edit', $value) }}">{{ $value->title }}</a></td>
    <td>0</td>
    <td>
        <div class="custom-control custom-switch">
            <input id="customSwitch_{{ $value->id }}" type="checkbox" name="is_active" class="custom-control-input"
                   @if(isset($value) && $value->is_active === true) checked @endif
                   data-id="{{ $value->id }}" data-item="article" data-status-name="is_active">
            <label for="customSwitch_{{ $value->id }}" class="custom-control-label"></label>
        </div>
    </td>
    <td><a href="{{ route('articles.show', $value) }}">{{ $value->name }}</a>
        <button type="button" class="btn btn-secondary">
            <a href="{{ route('articles.edit', $value) }}"
               style="color: inherit; text-decoration: none;">Редактировать</a>
        </button>
        <form action="{{ route('articles.destroy', $value) }}" method="post">
            @csrf
            @method('DELETE')
            <button style="width: 131px" type="submit" class="btn btn-danger"
                    onclick="if(!confirm('Вы уверены, что хотите удалить статью?')) return false" title="Удалить">
                Удалить
            </button>
        </form>
        <button style="width: 131px" type="button" class="btn btn-primary">
            <a href="{{ route('articles.show', $value) }}" style="color: inherit; text-decoration: none;">Страница</a>
        </button>
    </td>
</tr>
