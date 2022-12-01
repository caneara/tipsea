<?php

namespace App\Actions\Banner;

use App\Types\Action;
use App\Models\Banner;
use Illuminate\Support\Arr;
use App\Actions\Image\UpdateAction as ImageAction;

class UpdateAction extends Action
{
    /**
     * Update the given banner using the given payload.
     *
     */
    public static function execute(Banner $banner, array $payload) : Banner
    {
        static::make()->when(
            $payload['graphic'] ?? '',
            fn() => ImageAction::execute($banner, 'graphic', $payload['graphic'])
        );

        $attributes = Arr::except($payload, 'graphic');

        return attempt(fn() => tap($banner)->update($attributes));
    }
}
