<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'username' => 'required|string|min:2|max:255|regex:/[A-Za-zА-ЯЁа-яё]+/',
            'email' => 'required|string|max:255|email|unique:admin_users,email,' . $this->admin_user?->id,
            'password' => ($this->admin_user?->password ? 'nullable' : 'required') . '|string|min:5|max:255|regex:/^[A-Za-zА-ЯЁа-яё0-9]+$/|confirmed',
        ];
    }

}
