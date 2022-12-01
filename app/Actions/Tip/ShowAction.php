<?php

namespace App\Actions\Tip;

use App\Models\Tip;
use App\Models\User;
use App\Types\Action;
use App\Models\Comment;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Contracts\Pagination\CursorPaginator;

class ShowAction extends Action
{
    /**
     * The fields to make available.
     *
     */
    protected static array $fields = [
        'user' => [
            'id',
            'name',
            'handle',
            'biography',
            'website',
            'donate',
            'twitter',
            'github',
            'linkedin',
            'youtube',
            'facebook',
            'avatar',
        ],
        'banner' => [
            'id',
            'url',
            'graphic',
        ],
        'comments' => [
            'laravel_cte.id',
            'laravel_cte.path',
            'laravel_cte.depth',
            'laravel_cte.user_id',
            'laravel_cte.message',
            'laravel_cte.created_at',
            'laravel_cte.updated_at',
            'users.name',
            'users.handle',
            'users.avatar',
        ],
        'only' => [
            'id',
            'user',
            'banner',
            'title',
            'slug',
            'first_tag',
            'second_tag',
            'third_tag',
            'fourth_tag',
            'content',
            'attribution',
            'published_at',
            'metrics',
        ],
    ];

    /**
     * Retrieve the related information for the given tip.
     *
     */
    public static function execute(Tip $tip) : array
    {
        return $tip->load([
            'user:' . implode(',', static::$fields['user']),
            'banner:' . implode(',', static::$fields['banner']),
        ])->only(static::$fields['only']);
    }

    /**
     * Determine if the given student has bookmarked the given tip identifier.
     *
     */
    public static function bookmarked(User $student = null, int $id) : bool
    {
        return blank($student) ? false : $student->bookmarks()
            ->where('tip_id', $id)
            ->exists();
    }

    /**
     * Retrieve the comments associated with the given tip identifier.
     *
     */
    public static function comments(int $id) : CursorPaginator
    {
        $constraint = function($query) use ($id) {
            return $query->whereNull('parent_id')
                ->where('tip_id', $id);
        };

        return Comment::query()
            ->treeOf($constraint, 2)
            ->join('users', 'laravel_cte.user_id', '=', 'users.id')
            ->select(static::$fields['comments'])
            ->addSelect(DB::raw('CONCAT(`path`, ".", `laravel_cte`.`id`) AS `sort`'))
            ->orderBy('sort')
            ->cursorPaginate(30, static::$fields['comments']);
    }

    /**
     * Determine if the given student is a follower of the given teacher.
     *
     */
    public static function follower(User $student = null, User $teacher) : bool
    {
        return blank($student) ? false : $teacher->followers()
            ->where('student_id', $student->id)
            ->exists();
    }

    /**
     * Determine if the given student has liked the given tip identifier.
     *
     */
    public static function liked(User $student = null, int $id) : bool
    {
        return blank($student) ? false : $student->likes()
            ->where('tip_id', $id)
            ->exists();
    }

    /**
     * Retrieve several tips that may be of interest to the reader of the given tip.
     *
     */
    public static function related(User $user = null, array $tip) : Paginator
    {
        $payload = [
            'limit'  => 2,
            'filter' => 'tag',
            'search' => $tip['first_tag'],
        ];

        $query = Tip::query()
            ->where('tips.id', '!=', $tip['id'])
            ->whereRaw('`tips`.`id` >= FLOOR(1 + RAND() * (SELECT MAX(`id`) FROM `tips`))');

        return SearchAction::execute($query, $user, $payload);
    }
}
