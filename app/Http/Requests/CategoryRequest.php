<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'parent_id' =>
                'required|int' .
                ($this->input('parent_id') !== '0' ? '|exists:categories,id' : '') .
                ($this->filled('parent_id') ? '|not_in:' . $this->category?->id : ''),
            'name' => 'required|regex:/[A-Za-zА-ЯЁа-яё]+/|string|min:2|max:255|unique:categories,name,' . $this->category?->id,
            'content' => 'nullable|max:1000000',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png|dimensions:ratio=1',
            'is_active' => 'nullable' . ($this->filled('is_active') ? '|accepted' : ''),
        ];
    }

}
