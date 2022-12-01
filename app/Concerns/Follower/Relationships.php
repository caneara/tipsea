<?php

namespace App\Concerns\Follower;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Relationships
{
    /**
     * Retrieve the student associated with the follower.
     *
     */
    public function student() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Retrieve the teacher associated with the follower.
     *
     */
    public function teacher() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
