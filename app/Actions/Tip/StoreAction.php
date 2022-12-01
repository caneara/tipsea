<?php

namespace App\Actions\Tip;

use App\Models\Tip;
use App\Models\User;
use App\Types\Action;
use Illuminate\Support\Str;
use App\Actions\Image\StoreAction as ImageAction;

class StoreAction extends Action
{
    /**
     * Create a new tip using the given user and payload.
     *
     */
    public static function execute(User $user, array $payload) : Tip
    {
        $tip = attempt(fn() => $user->tips()->create($payload));

        ImageAction::execute($tip, 'card');

        return static::slug($tip);
    }

    /**
     * Assign a unique slug to the given tip.
     *
     */
    protected static function slug(Tip $tip) : Tip
    {
        $slug = Str::slug(implode('-', [$tip->id, $tip->title]));

        return attempt(fn() => tap($tip)->update(['slug' => $slug]));
    }
}
