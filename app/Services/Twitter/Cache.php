<?php

namespace App\Services\Twitter;

use App\Models\User;
use Illuminate\Http\Client\Response;

class Cache
{
    /**
     * Retrieve a temporary token belonging to the given user.
     *
     */
    public static function get(User $user, string $key) : string
    {
        return cache()->get("integration.{$user->id}.{$key}");
    }

    /**
     * Assign the given response's temporary tokens to the given user.
     *
     */
    public static function store(User $user, Response $response) : void
    {
        $time = now()->addMinutes(20);

        $prefix = "integration.{$user->id}";

        parse_str($response->body(), $result);

        cache()->put("{$prefix}.token", $result['oauth_token'], $time);
        cache()->put("{$prefix}.secret", $result['oauth_token_secret'], $time);
    }
}
