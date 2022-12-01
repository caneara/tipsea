<?php

namespace App\Concerns\Banner;

use App\Models\Tip;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait Relationships
{
    /**
     * Retrieve the tips associated with the banner.
     *
     */
    public function tips() : HasMany
    {
        return $this->hasMany(Tip::class);
    }

    /**
     * Retrieve the user associated with the banner.
     *
     */
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
