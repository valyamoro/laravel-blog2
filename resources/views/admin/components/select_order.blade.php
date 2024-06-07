<div style="margin-right: 20px">
    <select name="order" id="order" class="custom-select">
        <option value="desc" @if(request('order') === 'desc') selected @endif>По убыванию</option>
        <option value="asc" @if(request('order') === 'asc') selected @endif>По возрастанию</option>
    </select>
</div>
