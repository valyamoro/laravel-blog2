<div class="mb-3">
    <label for="title" class="form-label">Название</label>
    <input id="title" type="text" value="{{ old('title') ?? $item->title ?? '' }}" name="title" class="form-control @if(isset($errors)) @error('title') is-invalid @enderror @endif" aria-describedby="title" autocomplete="off">
    @if(isset($errors))
        @error('title')
        <span class="text-red">{{ $message }}</span>
        @enderror
    @endif
</div>
<div>
    <label for="category_id">Категория</label><br>
    <select id="category_id" name="category_id" class="custom-select">
        @if($categories->isNotEmpty())
            @foreach($categories as $idx => $name)
                <option value="{{ $idx }}" @if(isset($item) && $item->category->id === $idx) selected @endif>{{ $name }}</option>
            @endforeach
        @endif
    </select>
</div><br>
<div class="mb-3">
    <label for="annotation" class="form-label">Описание</label>
    <textarea id="annotation" name="annotation" class="form-control">{{ old('annotation') ?? $item->annotation ?? '' }}</textarea>
    @if(isset($errors))
        @error('annotation')
        <span class="text-red">{{ $message }}</span>
        @enderror
    @endif
</div>
<div class="mb-3">
    <label for="content" class="form-label">Содержимое статьи</label>
    <textarea id="content" name="content" class="form-control">{{ old('content') ?? $item->content ?? '' }}</textarea>
    @if(isset($errors))
        @error('content')
        <span class="text-red">{{ $message }}</span>
        @enderror
    @endif
</div>
<div class="custom-control custom-switch">
    <input id="customSwitch" type="checkbox" name="is_active" class="custom-control-input" @if(isset($item) && $item->is_active === true) checked @endif>
    <label for="customSwitch" class="custom-control-label">Активировать</label>
</div>
<br>
<div class="input-group mb-3">
    <label class="input-group-text" for="inputGroupFile">Upload</label>
    <input name="thumbnail" type="file" class="form-control" id="inputGroupFile">
</div>
@if(isset($errors))
    @error('thumbnail')
    <span class="text-red">{{ $message }}</span>
    @enderror
    <br>
@endif
@if(isset($item) && isset($item->thumbnail))
    @include('admin.articles.components.image_profile', ['item' => $item])
@endif
<br>
<br>
