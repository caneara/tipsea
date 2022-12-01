<?php

namespace App\Actions\Notification;

use App\Types\Action;
use App\Models\Notification;
use Illuminate\Support\Collection;

class ReadAction extends Action
{
    /**
     * Mark the given notifications as read.
     *
     */
    public static function execute(Collection $notifications) : void
    {
        $query = Notification::whereIn('id', $notifications->pluck('id'));

        static::make()->when(
            $notifications->isNotEmpty(),
            fn() => attempt(fn() => $query->update(['read_at' => now()]))
        );
    }
}
