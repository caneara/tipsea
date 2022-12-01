<?php

namespace App\Services\Twitter;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use GuzzleHttp\Subscriber\Oauth\Oauth1;
use Illuminate\Http\Client\PendingRequest;

class Client
{
    /**
     * Create an instance of the HTTP client.
     *
     */
    public static function make() : PendingRequest
    {
        return Http::acceptJson()
            ->withUserAgent(config('app.agent'))
            ->withOptions(['connect_timeout' => 5])
            ->timeout(app()->isProduction() ? 10 : 0)
            ->retry(app()->isProduction() ? 3 : 0, 5000, null, false);
    }

    /**
     * Generate OAuth middleware for the client.
     *
     */
    public static function middleware(string $token = null, string $secret = null) : Oauth1
    {
        return new Oauth1(array_filter([
            'consumer_key'    => config('services.twitter.api_key'),
            'consumer_secret' => config('services.twitter.api_secret'),
            'token'           => $token,
            'token_secret'    => $secret,
        ]));
    }

    /**
     * Generate OAuth middleware for the client using the given user.
     *
     */
    public static function middlewareFor(User $user) : Oauth1
    {
        return static::middleware(
            $user->integration['token'],
            $user->integration['secret'],
        );
    }
}
