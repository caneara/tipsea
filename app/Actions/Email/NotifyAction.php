<?php

namespace App\Actions\Email;

use App\Models\User;
use App\Types\Action;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VerifyEmailAddressNotification;

class NotifyAction extends Action
{
    /**
     * Send a verify email address notification to the given user.
     *
     */
    public static function execute(User $user) : void
    {
        attempt(fn() => $user->update(['email_verified_at' => null]));

        $user->notify(new VerifyEmailAddressNotification());
    }
}
