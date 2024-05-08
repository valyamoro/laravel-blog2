<div class="mb-3">
    <label for="name" class="form-label">Название</label>
    <input id="name" type="text" value="{{ old('name') ?? $item->name ?? '' }}" name="name" class="form-control @if(isset($errors)) @error('name') is-invalid @enderror @endif" aria-describedby="name" autocomplete="off">
    @if(isset($errors))
        @error('name')
        <span class="text-red">{{ $message }}</span>
        @enderror
    @endif
</div>
<div>
    <label for="parent_id">Родительская категория</label><br>
    <select id="parent_id" name="parent_id" class="custom-select">
        <option name="parent_id" value="0">Родительская</option>
        @if($categories->isNotEmpty())
            @foreach($categories as $idx => $name)
                <option value="{{ $idx }}" @if(isset($item) && $item->parent_id === $idx) selected @endif>{{ $name }}</option>
            @endforeach
        @endif
    </select>
</div><br>
<div class="mb-3">
    <label for="content" class="form-label">Описание</label>
    <textarea id="content" name="content" class="form-control">{{ old('content') ?? $item->content ?? '' }}</textarea>
    @if(isset($errors))
        @error('content')
        <span class="text-red">{{ $message }}</span>
        @enderror
    @endif
</div>
@if(isset($errors))
    @error('thumbnail')
    <span class="text-red">{{ $message }}</span>
    @enderror
    <br>
@endif
<div class="custom-control custom-switch">
    <input id="customSwitch" type="checkbox" name="is_active" class="custom-control-input" @if(isset($item) && $item->is_active === true) checked @endif>
    <label for="customSwitch" class="custom-control-label">Активировать</label>
</div>
<br>
<div class="input-group mb-3">
    <label class="input-group-text" for="inputGroupFile">Upload</label>
    <input name="thumbnail" type="file" class="form-control" id="inputGroupFile">
</div>
@if(isset($item) && isset($item->thumbnail))
    @include('admin.categories.components.image_profile', ['item' => $item])
@endif
<br>
<br>
