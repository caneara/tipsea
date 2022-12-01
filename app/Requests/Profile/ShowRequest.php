<?php

namespace App\Requests\Profile;

use App\Types\FormRequest;

class ShowRequest extends FormRequest
{
    /**
     * The profile fields to make available.
     *
     */
    public array $fields = [
        'id',
        'name',
        'handle',
        'biography',
        'website',
        'donate',
        'twitter',
        'github',
        'linkedin',
        'youtube',
        'facebook',
        'avatar',
        'metrics',
    ];
}
