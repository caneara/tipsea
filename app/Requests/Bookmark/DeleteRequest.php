<?php

namespace App\Requests\Bookmark;

use App\Models\Bookmark;
use App\Types\FormRequest;

class DeleteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     */
    public function authorize() : bool
    {
        return user()->can('delete', [Bookmark::class, $this->route('tip')]);
    }
}
