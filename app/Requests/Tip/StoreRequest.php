<?php

namespace App\Requests\Tip;

use App\Rules\ImageRule;
use App\Types\FormRequest;
use App\Rules\UniqueTagRule;
use Illuminate\Validation\Rule;

class StoreRequest extends FormRequest
{
    /**
     * Retrieve the validation rules that apply to the request.
     *
     */
    public function rules() : array
    {
        $exists = Rule::exists('banners', 'id')
            ->where('user_id', user()->id);

        return [
            'banner_id'    => ['bail', 'nullable', 'integer', $exists],
            'title'        => 'bail|required|string|min:5|max:100',
            'summary'      => 'bail|required|string|min:5|max:200',
            'teaser'       => 'bail|required|string|min:100|max:1000',
            'theme'        => 'bail|required|string|in:light,dark',
            'gradient'     => 'bail|required|integer|min:1|max:12',
            'card'         => ['bail', 'required', 'uuid', ImageRule::make()],
            'first_tag'    => ['bail', 'required', 'string', 'min:1', 'max:20', UniqueTagRule::make()],
            'second_tag'   => ['bail', 'nullable', 'string', 'min:1', 'max:20', UniqueTagRule::make()],
            'third_tag'    => ['bail', 'nullable', 'string', 'min:1', 'max:20', UniqueTagRule::make()],
            'fourth_tag'   => ['bail', 'nullable', 'string', 'min:1', 'max:20', UniqueTagRule::make()],
            'content'      => 'bail|required|string|min:100|max:5000',
            'attribution'  => 'bail|nullable|string|min:11|max:100|url',
            'shared'       => 'bail|required|boolean',
            'published_at' => 'bail|nullable|date|after_or_equal:now',
        ];
    }
}
