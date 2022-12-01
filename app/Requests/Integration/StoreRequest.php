<?php

namespace App\Requests\Integration;

use App\Types\FormRequest;
use App\Services\Twitter\Cache;

class StoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     */
    public function authorize() : bool
    {
        return blank(user()->integration);
    }

    /**
     * Retrieve the validation rules that apply to the request.
     *
     */
    public function rules() : array
    {
        return [
            'oauth_token'    => 'bail|required|string|in:' . Cache::get(user(), 'token'),
            'oauth_verifier' => 'bail|required|string',
        ];
    }
}
