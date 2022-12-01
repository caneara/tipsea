<?php

namespace App\Actions\Tip;

use App\Models\Tip;
use App\Types\Action;
use Illuminate\Support\Arr;
use App\Actions\Image\UpdateAction as ImageAction;

class UpdateAction extends Action
{
    /**
     * Update the given tip using the given payload.
     *
     */
    public static function execute(Tip $tip, array $payload) : Tip
    {
        ImageAction::execute($tip, 'card', $payload['card']);

        $attributes = Arr::except($payload, 'card');

        return attempt(fn() => tap($tip)->update($attributes));
    }
}
