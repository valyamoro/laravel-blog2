<div class="mb-3">
    <label for="name" class="form-label">Имя</label>
    <input id="name" type="text" value="{{ old('name') ?? $item->name ?? '' }}" name="name"
           class="form-control @if(isset($errors)) @error('name') is-invalid @enderror @endif"
           aria-describedby="username" autocomplete="off">
    @if(isset($errors))
        @error('name')
        <span class="text-red">{{ $message }}</span>
        @enderror
    @endif
</div>
<div class="custom-control custom-switch">
    <input id="customSwitch" type="checkbox" name="is_active"
           class="custom-control-input" @if(isset($item) && $item->is_active === true) checked @endif>
    <label for="customSwitch" class="custom-control-label">Активировать</label>
</div>
<br>
