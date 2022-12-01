<?php

namespace App\Concerns\Comment;

use App\Models\Tip;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Relationships
{
    /**
     * Retrieve the tip associated with the comment.
     *
     */
    public function tip() : BelongsTo
    {
        return $this->belongsTo(Tip::class);
    }

    /**
     * Retrieve the user associated with the comment.
     *
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
