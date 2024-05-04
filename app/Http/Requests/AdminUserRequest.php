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
        $id = $this->admin_user->id ?? null;

        return [
            'username' => 'required|string|min:2|max:255|regex:/[A-Za-zА-ЯЁа-яё]+/',
            'email' => 'required|string|max:255|email|unique:admin_users,email,' . $id,
            'password' => $this->isRequired() . 'string|min:5|max:255|regex:/^[A-Za-zА-ЯЁа-яё0-9]+$/' . $this->isConfirmed(),
        ];
    }

    private function isRequired(): string
    {
        return !isset($this->admin_user) ? 'required|' : 'nullable|';
    }

    private function isConfirmed(): string
    {
        return !isset($this->admin_user) ? '|confirmed' : '';
    }

}
