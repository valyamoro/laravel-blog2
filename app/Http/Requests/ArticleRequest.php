<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'category_id' => 'required|int|exists:categories,id',
            'title' => 'required|regex:/[A-Za-zА-ЯЁа-яё]+/|string|min:2|max:255|unique:articles,title,' . $this->article?->id,
            'annotation' => 'nullable|max:1000',
            'content' => 'required|min:1000|max:1000000',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png|dimensions:ratio=1',
            'is_active' => 'nullable' . ($this->filled('is_active') ? '|accepted' : ''),
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ];
    }

}
