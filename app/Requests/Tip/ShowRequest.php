<?php

namespace App\Requests\Tip;

use App\Types\FormRequest;
use App\Policies\TipPolicy;

class ShowRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     */
    public function authorize() : bool
    {
        return TipPolicy::make()->show(user(), $this->route('tip'));
    }
}
