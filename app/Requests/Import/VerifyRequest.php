<?php

namespace App\Requests\Import;

use App\Types\FormRequest;
use App\Rules\ImportTipRule;

class VerifyRequest extends FormRequest
{
    /**
     * Retrieve the validation rules that apply to the request.
     *
     */
    public function rules() : array
    {
        return [
            'payload' => ['bail', 'required', 'json', ImportTipRule::make(user(), true)],
        ];
    }
}
