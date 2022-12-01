<?php

namespace App\Services\Twitter;

use App\Models\Tip;
use App\Storage\Image;
use Illuminate\Support\Str;

class Tweet
{
    /**
     * Publish the media image associated with the given tip.
     *
     */
    protected static function image(Tip $tip) : string
    {
        $url = 'https://upload.twitter.com/1.1/media/upload.json';

        $payload = [
            'media_category' => 'tweet_image',
        ];

        $response = Client::make()
            ->withMiddleware(Client::middlewareFor($tip->user))
            ->withOptions(['auth' => 'oauth'])
            ->attach('media', Image::get($tip->card))
            ->post($url, $payload)
            ->throw();

        return $response['media_id'];
    }

    /**
     * Post the primary tweet using the given tip and media reference.
     *
     */
    protected static function first(Tip $tip, string $media) : string
    {
        $url = 'https://api.twitter.com/1.1/statuses/update.json';

        $status = Str::of('#')
            ->append(Str::ucfirst($tip->first_tag))
            ->append(" Tip:\n\n{$tip->summary}")
            ->rtrim('.')
            ->finish("...\n\n👇 example in link below")
            ->toString();

        $payload = [
            'status'    => $status,
            'media_ids' => $media,
            'trim_user' => true,
        ];

        $response = Client::make()
            ->withMiddleware(Client::middlewareFor($tip->user))
            ->withOptions(['auth' => 'oauth'])
            ->asForm()
            ->post($url, $payload)
            ->throw();

        return $response['id'];
    }

    /**
     * Post the secondary tweet using the given tip and reply reference.
     *
     */
    protected static function second(Tip $tip, string $reply) : void
    {
        $url = 'https://api.twitter.com/1.1/statuses/update.json';

        $status = route('tips.show', ['tip' => $tip, 'no_embed' => true]);

        $payload = [
            'status'                => "🔗 {$status}",
            'in_reply_to_status_id' => $reply,
            'trim_user'             => true,
        ];

        Client::make()
            ->withMiddleware(Client::middlewareFor($tip->user))
            ->withOptions(['auth' => 'oauth'])
            ->asForm()
            ->post($url, $payload)
            ->throw();
    }

    /**
     * Post the alternative text using the given tip and media reference.
     *
     */
    protected static function text(Tip $tip, string $media) : void
    {
        $url = 'https://upload.twitter.com/1.1/media/metadata/create.json';

        $text = Str::of($tip->teaser)
            ->replace('"', "'")
            ->limit(400)
            ->toString();

        $payload = [
            'media_id' => $media,
            'alt_text' => compact('text'),
        ];

        Client::make()
            ->withMiddleware(Client::middlewareFor($tip->user))
            ->withOptions(['auth' => 'oauth'])
            ->bodyFormat('json')
            ->contentType('application/json; charset=UTF-8')
            ->post($url, $payload)
            ->throw();
    }

    /**
     * Post a set of tweets for the given tip.
     *
     */
    public static function publish(Tip $tip) : void
    {
        $media = static::image($tip);

        static::text($tip, $media);

        $reply = static::first($tip, $media);

        sleep(config('services.twitter.delay'));

        static::second($tip, $reply);
    }
}
