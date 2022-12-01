<?php

namespace App\Requests\Integration;

use App\Types\FormRequest;

class DeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     */
    public function authorize() : bool
    {
        return filled(user()->integration);
    }
}
