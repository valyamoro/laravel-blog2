<div style="margin-right: 20px">
    <select id="pagination" name="pagination" class="custom-select">
        @foreach($paginationValues as $idx => $value)
            <option value="{{ $idx }}" @if(request('pagination') === $idx) selected @endif data-count="{{ $paginator->count() }}">{{ $value }}</option>
        @endforeach
    </select>
</div>
