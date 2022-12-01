<?php

namespace App\Services\Twitter;

use App\Models\User;

class Auth
{
    /**
     * Generate an authorization link for the given user.
     *
     */
    public static function getAuthorizationUrl(User $user) : string
    {
        $url = 'https://api.twitter.com/oauth/authorize';

        $parameters = http_build_query([
            'oauth_token' => Cache::get($user, 'token'),
        ]);

        return "{$url}?{$parameters}";
    }

    /**
     * Obtain an access token using the given payload.
     *
     */
    public static function obtainAccessToken(array $payload) : array
    {
        $url = 'https://api.twitter.com/oauth/access_token';

        $response = Client::make()
            ->withMiddleware(Client::middleware($payload['oauth_token']))
            ->withOptions(['auth' => 'oauth'])
            ->asForm()
            ->post($url, $payload)
            ->throw();

        parse_str($response->body(), $result);

        return [
            'token'  => $result['oauth_token'],
            'secret' => $result['oauth_token_secret'],
            'id'     => $result['user_id'],
            'handle' => $result['screen_name'],
        ];
    }

    /**
     * Obtain a request token for the given user.
     *
     */
    public static function obtainRequestToken(User $user) : void
    {
        $url = 'https://api.twitter.com/oauth/request_token';

        $payload = [
            'oauth_callback' => urlencode(route('integration.create')),
        ];

        $response = Client::make()
            ->withMiddleware(Client::middleware())
            ->withOptions(['auth' => 'oauth'])
            ->post($url, $payload)
            ->throw();

        Cache::store($user, $response);
    }
}
