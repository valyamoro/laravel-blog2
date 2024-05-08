<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TagRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        $this->merge(['is_active' => (bool)$this->input('is_active')]);

        $id = $this->tag?->id;

        return [
            'name' => 'required|string|min:3|max:255|regex:/[A-Za-zА-ЯЁа-яё]+/|unique:tags,name,' . $id,
            'is_active' => 'boolean',
        ];
    }

}
