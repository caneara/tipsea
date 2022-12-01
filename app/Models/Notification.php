<?php

namespace App\Models;

use App\Types\Model;
use App\Enums\NotificationType;
use Illuminate\Database\Eloquent\Builder;
use App\Concerns\Notification\Relationships;
use Illuminate\Database\Eloquent\MassPrunable;

class Notification extends Model
{
    use MassPrunable;
    use Relationships;

    /**
     * The attributes that should be cast to specific types.
     *
     */
    protected $casts = [
        'id'      => 'integer',
        'user_id' => 'integer',
        'tip_id'  => 'integer',
        'type'    => NotificationType::class,
        'message' => 'string',
        'read_at' => 'datetime',
    ];

    /**
     * Generate the prunable model query.
     *
     */
    public function prunable() : Builder
    {
        return static::where('created_at', '<=', now()->subDays(30));
    }
}
