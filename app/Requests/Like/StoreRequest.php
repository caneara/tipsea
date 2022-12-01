<?php

namespace App\Requests\Like;

use App\Models\Like;
use App\Types\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     */
    public function authorize() : bool
    {
        return user()->can('store', [Like::class, $this->route('tip')]);
    }
}
