<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('admin')->check();
    }

    public function rules(): array
    {
        return [
            'parent_id' => 'nullable|int' . ($this->input('parent_id') !== '0' ? '|exists:comments,id' : ''),
            'article_id' => 'nullable|int|exists:articles,id',
            'comment' => 'nullable|string|min:5|max:255',
        ];
    }

}
