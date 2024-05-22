<div class="custom-control custom-switch">
    <input id="customSwitch" type="checkbox" name="is_active" class="custom-control-input"
           @if(isset($item) && $item->is_active === true) checked @endif>
    <label for="customSwitch" class="custom-control-label"></label>
</div>
<div>
    <label for="comment"><strong>Содержимое комментария</strong></label>
    <textarea id="comment" name="comment" class="form-control">{{ old('comment') ?? $item->comment ?? '' }}</textarea>
</div>
<input type="hidden" name="article_id" value="{{ $item->article()->get()->first()->id }}">
@error('comment')
<span class="text-red">{{ $message }}</span>
<br>
@enderror
