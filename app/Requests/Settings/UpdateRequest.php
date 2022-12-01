<?php

namespace App\Requests\Settings;

use App\Types\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Retrieve the validation rules that apply to the request.
     *
     */
    public function rules() : array
    {
        return [
            'notifications_email_comments' => 'bail|required|boolean',
        ];
    }
}
