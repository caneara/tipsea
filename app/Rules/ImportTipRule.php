<?php

namespace App\Rules;

use App\Types\Rule;
use Illuminate\Validation\Rule as Validation;

class ImportTipRule extends Rule
{
    /**
     * Retrieve the validation error message.
     *
     */
    public function message() : string
    {
        return 'The JSON file does not conform to the required schema.';
    }

    /**
     * Determine if the validation rule passes.
     *
     */
    public function passes($attribute, $value) : bool
    {
        foreach (json_decode($value, true) as $tip) {
            if (validator($tip, $this->rules())->fails()) {
                return false;
            }
        }

        return true;
    }

    /**
     * Retrieve the validation rules that apply to each item.
     *
     */
    public function rules() : array
    {
        $exists = Validation::exists('banners', 'id')
            ->where('user_id', $this->parameters[0]?->id);

        return array_filter([
            'banner_id'    => ['bail', 'nullable', 'integer', $exists],
            'title'        => 'bail|required|string|min:5|max:100',
            'summary'      => 'bail|required|string|min:5|max:200',
            'teaser'       => 'bail|required|string|min:100|max:1000',
            'theme'        => 'bail|required|string|in:light,dark',
            'gradient'     => 'bail|required|integer|min:1|max:12',
            'card'         => $this->parameters[1] ? null : ['bail', 'required', 'uuid', ImageRule::make()],
            'first_tag'    => ['bail', 'required', 'string', 'min:1', 'max:20', UniqueTagRule::make()],
            'second_tag'   => ['sometimes', 'bail', 'nullable', 'string', 'min:1', 'max:20', UniqueTagRule::make()],
            'third_tag'    => ['sometimes', 'bail', 'nullable', 'string', 'min:1', 'max:20', UniqueTagRule::make()],
            'fourth_tag'   => ['sometimes', 'bail', 'nullable', 'string', 'min:1', 'max:20', UniqueTagRule::make()],
            'content'      => 'bail|required|string|min:100|max:5000',
            'attribution'  => 'sometimes|bail|nullable|string|min:11|max:100|url',
            'shared'       => ['bail', 'required', Validation::in(true)],
            'published_at' => 'bail|required|date|before:now',
        ]);
    }
}
