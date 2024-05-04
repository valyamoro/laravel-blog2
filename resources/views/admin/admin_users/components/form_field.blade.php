<div class="mb-3">
    <label for="username" class="form-label">Имя</label>
    <input id="username" type="text" value="{{ old('username') ?? $item->username ?? '' }}" name="username"
           class="form-control @if(isset($errors)) @error('username') is-invalid @enderror @endif"
           aria-describedby="username" autocomplete="off">
    @if(isset($errors))
        @error('username')
        <span class="text-red">{{ $message }}</span>
        @enderror
    @endif
</div>
<div class="mb-3">
    <label for="email" class="form-label">Почта</label>
    <input id="email" type="text" value="{{ old('email') ?? $item->email ?? '' }}" name="email"
           class="form-control @if(isset($errors)) @error('email') is-invalid @enderror @endif"
           aria-describedby="email" autocomplete="off">
    @if(isset($errors))
        @error('email')
        <span class="text-red">{{ $message }}</span>
        @enderror
    @endif
</div>
<div class="mb-3">
    <label for="password" class="form_label">Пароль</label>
    <input id="password" type="password" name="password"
           class="form-control @if(isset($errors)) @error('password') is-invalid @enderror @endif"
           autocomplete="off">
    @if(isset($errors))
        @error('password')
        <span class="text-red">{{ $message }}</span>
        @enderror
    @endif
</div>
<div class="mb-3">
    <label for="password_confirmation" class="form-label">Подтверждение пароля</label>
    <input id="password_confirmation" type="password" name="password_confirmation"
           class="form-control @if(isset($errors)) @error('password') is-invalid @enderror @endif"
           autocomplete="off">
</div>
<div class="custom-control custom-switch">
    <input id="customSwitch" type="checkbox" name="is_banned"
           class="custom-control-input" @if(isset($item) && $item->is_banned === true) checked @endif>
    <label for="customSwitch" class="custom-control-label">Забанить</label>
</div>
<br>
