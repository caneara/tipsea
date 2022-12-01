<?php

namespace App\Concerns\User;

use App\Models\Tip;
use App\Models\Like;
use App\Models\Banner;
use App\Models\Comment;
use App\Models\Bookmark;
use App\Models\Follower;
use App\Models\Notification;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait Relationships
{
    /**
     * Retrieve the banners associated with the user.
     *
     */
    public function banners() : HasMany
    {
        return $this->hasMany(Banner::class);
    }

    /**
     * Retrieve the bookmarks associated with the user.
     *
     */
    public function bookmarks() : HasMany
    {
        return $this->hasMany(Bookmark::class);
    }

    /**
     * Retrieve the comments associated with the user.
     *
     */
    public function comments() : HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Retrieve the followers associated with the user.
     *
     */
    public function followers() : HasMany
    {
        return $this->hasMany(Follower::class, 'teacher_id');
    }

    /**
     * Retrieve the likes associated with the user.
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
        return $this->hasMany(Notification::class, 'teacher_id');
    }

    /**
     * Retrieve the tips associated with the user.
     *
     */
    public function tips() : HasMany
    {
        return $this->hasMany(Tip::class);
    }
}
