<?php

namespace App\Requests\Register;

use App\Types\FormRequest;
use App\Rules\ForbiddenContentRule;
use Illuminate\Validation\Rules\Password;

class StoreRequest extends FormRequest
{
    /**
     * Retrieve the validation rules that apply to the request.
     *
     */
    public function rules() : array
    {
        $name   = ForbiddenContentRule::make('names', '');
        $handle = ForbiddenContentRule::make('names', '');
        $email  = ForbiddenContentRule::make('domains', '');

        return [
            'name'     => ['bail', 'required', 'string', 'min:1', 'max:50', $name],
            'handle'   => ['bail', 'required', 'string', 'min:3', 'max:30', $handle, 'unique:users,handle'],
            'email'    => ['bail', 'required', 'string', 'min:6', 'max:255', 'email', $email, 'unique:users,email'],
            'password' => ['bail', 'required', 'string', 'max:255', 'confirmed', Password::defaults()],
            'terms'    => 'bail|required|accepted|exclude',
        ];
    }
}
