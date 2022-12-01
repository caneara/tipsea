<?php

namespace App\Concerns\Tip;

use App\Models\Like;
use App\Models\User;
use App\Models\Banner;
use App\Models\Comment;
use App\Models\Bookmark;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Relationships
{
    /**
     * Retrieve the banner associated with the tip.
     *
     */
    public function banner() : BelongsTo
    {
        return $this->belongsTo(Banner::class);
    }

    /**
     * Retrieve the bookmarks associated with the tip.
     *
     */
    public function bookmarks() : HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Retrieve the comments associated with the tip.
     *
     */
    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Retrieve the likes associated with the tip.
     *
     */
    public function likes() : HasMany
    {
        return $this->hasMany(Like::class);
    }

    /**
     * Retrieve the notifications associated with the user.
     *
     */
    public function notifications() : HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Retrieve the user associated with the tip.
     *
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
