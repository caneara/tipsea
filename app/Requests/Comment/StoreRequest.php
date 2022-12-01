<?php

namespace App\Requests\Comment;

use App\Models\Comment;
use App\Types\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     */
    public function authorize() : bool
    {
        return user()->can('store', [Comment::class, $this->route('tip')]);
    }

    /**
     * Retrieve the validation rules that apply to the request.
     *
     */
    public function rules() : array
    {
        return [
            'message' => 'bail|required|string|min:1|max:500',
        ];
    }
}
