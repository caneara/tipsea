<?php

namespace App\Requests\Banner;

use App\Rules\ImageRule;
use App\Types\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     */
    public function authorize() : bool
    {
        return user()->can('update', $this->route('banner'));
    }

    /**
     * Retrieve the validation rules that apply to the request.
     *
     */
    public function rules() : array
    {
        return [
            'name'    => 'bail|required|string|min:1|max:30',
            'url'     => 'bail|required|string|min:11|max:100|url',
            'graphic' => ['bail', 'nullable', 'uuid', ImageRule::make()],
        ];
    }
}
