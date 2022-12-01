<?php

namespace App\Requests\Bookmark;

use App\Types\FormRequest;

class IndexRequest extends FormRequest
{
    /**
     * Retrieve the validation rules that apply to the request.
     *
     */
    public function rules() : array
    {
        return [
            'filter' => 'sometimes|bail|nullable|string|in:title,tag',
            'search' => 'sometimes|bail|nullable|string|min:1|max:100',
        ];
    }
}
