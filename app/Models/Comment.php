<?php

namespace App\Models;

use App\Types\Model;
use App\Concerns\Comment\Relationships;
use Staudenmeir\LaravelAdjacencyList\Eloquent\HasRecursiveRelationships;

class Comment extends Model
{
    use Relationships;
    use HasRecursiveRelationships;

    /**
     * The attributes that should be cast to specific types.
     *
     */
    protected $casts = [
        'id'        => 'integer',
        'user_id'   => 'integer',
        'tip_id'    => 'integer',
        'parent_id' => 'integer',
        'message'   => 'string',
    ];
}
