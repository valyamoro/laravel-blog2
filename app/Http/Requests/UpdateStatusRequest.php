<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $statusName = $this->input('status_name');

        return [
            $statusName => 'nullable' . (($this->input($statusName) === true) ? '|accepted' : ''),
        ];
    }
}
