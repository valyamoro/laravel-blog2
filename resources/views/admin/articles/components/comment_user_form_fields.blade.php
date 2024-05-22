<div class="mb-3">
    <label for="username" class="form-label">Имя</label>
    <input id="username" type="text" value="{{ old('username') ?? $item->username ?? '' }}"
           name="username"
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
