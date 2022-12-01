<?php

namespace App\Requests\Tip;

use App\Types\FormRequest;

class EditRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     */
    public function authorize() : bool
    {
        return user()->can('edit', $this->route('tip'));
    }
}
