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
        $id = $this->category?->id;
        $parentId = $this->parent_id;

        $this->merge(['is_active' => (bool)$this->is_active]);

        return [
            'parent_id' => 'required|int' . ($parentId === '0' ? '' : '|exists:categories,id'),
            'name' => 'required|regex:/[A-Za-zА-ЯЁа-яё]+/|string|min:2|max:255|unique:categories,name,' . $id,
            'content' => 'nullable|max:1000000',
            'thumbnail' => 'nullable|image|mimes:jpeg,jpg,png|dimensions:ratio=1',
            'is_active' => 'boolean',
        ];
    }

}
