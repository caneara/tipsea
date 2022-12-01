<?php

namespace App\Actions\Tip;

use App\Models\Like;
use App\Models\User;
use App\Types\Action;
use App\Models\Bookmark;
use App\Models\Follower;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Query\Expression;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SearchAction extends Action
{
    /**
     * The fields to retrieve.
     *
     */
    protected static array $fields = [
        'tips.id',
        'tips.user_id',
        'tips.title',
        'tips.slug',
        'tips.summary',
        'tips.card',
        'tips.first_tag',
        'tips.second_tag',
        'tips.third_tag',
        'tips.fourth_tag',
        'tips.metrics',
        'tips.published_at',
        'users.name AS user',
        'users.handle',
        'users.avatar',
    ];

    /**
     * Retrieve a selection of code tips using the given payload.
     *
     */
    public static function execute(mixed $query, User $user = null, array $payload = []) : Paginator
    {
        $limit      = Arr::get($payload, 'limit', 6);
        $filter     = Arr::get($payload, 'filter', '');
        $search     = Arr::get($payload, 'search', '');
        $liked      = Arr::get($payload, 'liked', true);
        $ordering   = Arr::get($payload, 'ordering', '');
        $follower   = Arr::get($payload, 'follower', true);
        $published  = Arr::get($payload, 'published', true);
        $bookmarked = Arr::get($payload, 'bookmarked', true);

        return $query
            ->select(static::$fields)
            ->from(static::index($filter ?? ''))
            ->join('users', 'tips.user_id', '=', 'users.id')
            ->when($liked, fn($query) => static::liked($query, $user))
            ->when($published,  fn($query) => static::published($query))
            ->when($follower,  fn($query) => static::follower($query, $user))
            ->when($bookmarked, fn($query) => static::bookmarked($query, $user))
            ->when($filter === 'tag', fn($query) => static::tag($query, $search))
            ->when($filter === 'title', fn($query) => $query->whereFullText('title', $search))
            ->when($ordering && ! $filter, fn($query) => $query->orderByDesc($ordering))
            ->unless($filter || $ordering, fn($query) => $query->orderByDesc('tips.published_at'))
            ->unless($filter || $ordering, fn($query) => $query->orderByDesc('tips.id'))
            ->simplePaginate($limit, static::$fields);
    }

    /**
     * Require that the tip knows if it has been bookmarked by the given user.
     *
     */
    protected static function bookmarked(HasMany | Builder $query, User $user = null) : HasMany | Builder
    {
        $sub = Bookmark::query()
            ->whereColumn('bookmarks.tip_id', 'tips.id')
            ->where('bookmarks.user_id', $user?->id);

        return $query->unless($user, function($query) {
            return $query->addSelect(DB::raw('0 AS bookmarked'));
        })->when($user, function($query) use ($sub) {
            return $query->selectRaw("EXISTS({$sub->toSql()}) AS 'bookmarked'", $sub->getBindings());
        });
    }

    /**
     * Require that the tip knows if its teacher is being followed by the given user.
     *
     */
    protected static function follower(HasMany | Builder $query, User $user = null) : HasMany | Builder
    {
        $sub = Follower::query()
            ->whereColumn('users.id', 'tips.user_id')
            ->where('followers.student_id', $user?->id);

        return $query->unless($user, function($query) {
            return $query->addSelect(DB::raw('0 AS follower'));
        })->when($user, function($query) use ($sub) {
            return $query->selectRaw("EXISTS({$sub->toSql()}) AS 'follower'", $sub->getBindings());
        });
    }

    /**
     * Determine the appropriate database index to use.
     *
     */
    protected static function index(string $filter = '') : Expression
    {
        return DB::raw('`tips`' . match ($filter) {
            'tag'   => 'FORCE INDEX (`tips_first_tag_second_tag_third_tag_fourth_tag_index`)',
            'title' => 'FORCE INDEX (`tips_title_fulltext`)',
            default => '',
        });
    }

    /**
     * Require that the tip knows if it has been liked by the given user.
     *
     */
    protected static function liked(HasMany | Builder $query, User $user = null) : HasMany | Builder
    {
        $sub = Like::query()
            ->whereColumn('likes.tip_id', 'tips.id')
            ->where('likes.user_id', $user?->id);

        return $query->unless($user, function($query) {
            return $query->addSelect(DB::raw('0 AS liked'));
        })->when($user, function($query) use ($sub) {
            return $query->selectRaw("EXISTS({$sub->toSql()}) AS 'liked'", $sub->getBindings());
        });
    }

    /**
     * Restrict the given query to tips that have been published.
     *
     */
    protected static function published(HasMany | Builder $query) : HasMany | Builder
    {
        return $query->where(function($query) {
            return $query->whereNotNull('tips.published_at')
                ->where('tips.published_at', '<=', now());
        });
    }

    /**
     * Limit the results to tips that possess the given tag.
     *
     */
    protected static function tag(HasMany | Builder $query, string $tag) : HasMany | Builder
    {
        return $query->where(function($query) use ($tag) {
            $query->where('first_tag', $tag)
                ->orWhere('second_tag', $tag)
                ->orWhere('third_tag', $tag)
                ->orWhere('fourth_tag', $tag);
        });
    }
}
