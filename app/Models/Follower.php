<?php

namespace App\Models;

use App\Types\Model;
use App\Concerns\Follower\Relationships;

class Follower extends Model
{
    use Relationships;

    /**
     * The attributes that should be cast to specific types.
     *
     */
    protected $casts = [
        'id'         => 'integer',
        'teacher_id' => 'integer',
        'student_id' => 'integer',
    ];
}
