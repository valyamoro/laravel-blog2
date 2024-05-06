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

        $allowDimensions = 'min_width=100,min_height=100,max_width=3840,max_height=2160';
        $allowExtensions = 'jpeg,jpg,png';

        return [
            'parent_id' => 'required|int',
            'name' => 'required|regex:/[A-Za-zА-ЯЁа-яё]+/|string|min:2|max:255|unique:categories,name,' . $id,
            'content' => 'nullable|max:1000000',
            'thumbnail' => 'nullable|image|mimes:' . $allowExtensions . '|dimensions:' . $allowDimensions,
            'is_active' => 'nullable',
        ];
    }

}
