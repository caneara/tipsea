<?php

namespace App\Concerns\Notification;

use App\Models\Tip;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Relationships
{
    /**
     * Retrieve the student associated with the notification.
     *
     */
    public function student() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retrieve the teacher associated with the notification.
     *
     */
    public function teacher() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retrieve the tip associated with the notification.
     *
     */
    public function tip() : BelongsTo
    {
        return $this->belongsTo(Tip::class);
    }
}
