<?php

namespace App\Actions\Notification;

use App\Models\User;
use App\Types\Action;
use App\Actions\User\UpdateAction;
use Illuminate\Contracts\Pagination\Paginator;

class ViewAction extends Action
{
    /**
     * The fields to retrieve.
     *
     */
    protected static array $fields = [
        'notifications.id',
        'notifications.tip_id',
        'notifications.type',
        'notifications.message',
        'notifications.read_at',
        'notifications.created_at',
        'tips.title',
        'tips.slug',
        'users.name AS user',
        'users.handle',
        'users.avatar',
    ];

    /**
     * Retrieve the notifications belonging to the given user.
     *
     */
    public static function execute(User $user) : Paginator
    {
        UpdateAction::execute($user, ['metrics->notifications' => 0]);

        $results = $user->notifications()
            ->join('tips', 'notifications.tip_id', '=', 'tips.id')
            ->join('users', 'notifications.student_id', '=', 'users.id')
            ->orderByRaw('read_at IS NOT NULL')
            ->orderByDesc('read_at')
            ->simplePaginate(20, static::$fields);

        ReadAction::execute($results->getCollection()->whereNull('read_at'));

        return $results;
    }
}
