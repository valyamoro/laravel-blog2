<div style="margin-right: 20px">
    <select id="pagination" name="pagination" class="custom-select">
        @foreach($perPages as $idx => $name)
            <option value="{{ $idx }}" @if(request('pagination') === $idx) selected @endif data-count="{{ $paginator->count() }}">{{ $name }}</option>
        @endforeach
    </select>
</div>
