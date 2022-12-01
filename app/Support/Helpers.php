<?php

use App\Models\User;
use App\Support\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

/**
 * Attempt to perform the given database task.
 *
 */
function attempt(Closure $action, bool $transaction = true, int $times = 40, int $delay = 250) : mixed
{
    $closure = $transaction ? fn() => DB::transaction($action, 10) : $action;

    return retry($times, $closure, $delay);
}

/**
 * Create a new Carbon instance for the current date and time.
 *
 */
function now(DateTimeZone | string | null $zone = null) : Carbon
{
    return Carbon::now($zone);
}

/**
 * Retrieve one of the given user's settings.
 *
 */
function setting(User $user, string $key, mixed $default = false) : mixed
{
    return Arr::get($user->settings, $key, $default);
}

/**
 * Generate an ordered version 4 UUID.
 *
 */
function uuid() : string
{
    return Str::orderedUuid()->toString();
}

/**
 * Retrieve the current user.
 *
 */
function user() : User | null
{
    return auth()->user();
}
