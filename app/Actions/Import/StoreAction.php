<?php

namespace App\Actions\Import;

use App\Models\User;
use App\Types\Action;
use Illuminate\Support\Arr;
use App\Actions\Tip\StoreAction as StoreTipAction;

class StoreAction extends Action
{
    /**
     * Create one or more tips for the given user using the given data.
     *
     */
    public static function execute(User $user, array $data) : void
    {
        foreach (json_decode($data['payload'], true) as $tip) {
            StoreTipAction::execute(
                $user,
                Arr::map($tip, fn($v) => $v === '' ? null : $v)
            );
        }
    }
}
