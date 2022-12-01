<?php

namespace App\Requests\Account;

use App\Types\FormRequest;
use App\Rules\ForbiddenContentRule;

class UpdateRequest extends FormRequest
{
    /**
     * Retrieve the validation rules that apply to the request.
     *
     */
    public function rules() : array
    {
        $name   = ForbiddenContentRule::make('names', user()->name);
        $handle = ForbiddenContentRule::make('names', user()->handle);
        $email  = ForbiddenContentRule::make('domains', user()->email);

        return [
            'name'      => ['sometimes', 'bail', 'required', 'string', 'min:1', 'max:50', $name],
            'handle'    => ['sometimes', 'bail', 'required', 'string', 'min:3', 'max:30', $handle, 'unique:users,handle,' . user()->id],
            'email'     => ['sometimes', 'bail', 'required', 'string', 'min:6', 'max:255', 'email', $email, 'unique:users,email,' . user()->id],
            'biography' => 'sometimes|bail|nullable|string|min:5|max:500',
            'website'   => 'sometimes|bail|nullable|string|min:11|max:100|url',
            'donate'    => 'sometimes|bail|nullable|string|min:11|max:100|url',
            'github'    => 'sometimes|bail|nullable|string|min:11|max:100|url',
            'twitter'   => 'sometimes|bail|nullable|string|min:11|max:100|url',
            'linkedin'  => 'sometimes|bail|nullable|string|min:11|max:100|url',
            'youtube'   => 'sometimes|bail|nullable|string|min:11|max:100|url',
            'facebook'  => 'sometimes|bail|nullable|string|min:11|max:100|url',
        ];
    }
}
