<?php

namespace App\Rules;

use App\Types\Rule;

class PublishedDateRule extends Rule
{
    /**
     * Retrieve the validation error message.
     *
     */
    public function message() : string
    {
        return 'The date must be now or in the future.';
    }

    /**
     * Determine if the validation rule passes.
     *
     */
    public function passes($attribute, $value) : bool
    {
        $date = $this->parameters[0]->published_at;

        if (! $date) {
            $rules = 'bail|nullable|date|after_or_equal:now';
        } elseif ($date->isFuture()) {
            $rules = 'bail|required|date|after_or_equal:now';
        } else {
            $rules = BlankRule::make();
        }

        return ! validator(['date' => $value], ['date' => $rules])->fails();
    }
}
