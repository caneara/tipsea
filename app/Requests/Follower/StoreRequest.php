<?php

namespace App\Requests\Follower;

use App\Models\Follower;
use App\Types\FormRequest;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     */
    public function authorize() : bool
    {
        return user()->can('store', [Follower::class, $this->route('teacher')]);
    }
}
