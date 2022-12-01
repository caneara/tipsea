<?php

namespace App\Responses;

use App\Models\Tip;
use App\Models\User;
use App\Types\Model;
use App\Storage\Image;
use Illuminate\Support\Str;

class Meta
{
    /**
     * Generate the Open Graph and Twitter Card meta tags for the given model.
     *
     */
    public static function create(Model $model) : array
    {
        return [
            'alt'         => static::getAltImageText($model),
            'card'        => static::getCard($model),
            'description' => static::getDescription($model),
            'image'       => static::getImage($model),
            'title'       => static::getTitle($model),
            'type'        => static::getType($model),
            'url'         => static::getUrl($model),
        ];
    }

    /**
     * Retrieve the alternate image text to use for the meta tag.
     *
     */
    protected static function getAltImageText(Model $model) : string
    {
        return match (get_class($model)) {
            Tip::class  => Str::of($model->teaser)->replace('"', "'")->limit(400)->toString(),
            User::class => '',
        };
    }

    /**
     * Retrieve the card type to use for the meta tag.
     *
     */
    protected static function getCard(Model $model) : string
    {
        return match (get_class($model)) {
            Tip::class  => 'summary_large_image',
            User::class => 'summary',
        };
    }

    /**
     * Retrieve the description to use for the meta tag.
     *
     */
    protected static function getDescription(Model $model) : string
    {
        $value = match (get_class($model)) {
            Tip::class  => $model->summary,
            User::class => "{$model->name} is on TipSea. Write, share and discover code tips.",
        };

        return Str::limit($value, 197);
    }

    /**
     * Retrieve the image to use for the meta tag.
     *
     */
    protected static function getImage(Model $model) : string
    {
        return match (get_class($model)) {
            Tip::class  => $model->card ? Image::url($model->card) : asset('img/tip.png'),
            User::class => $model->avatar ? Image::url($model->avatar) : asset('img/user.png'),
        };
    }

    /**
     * Retrieve the title to use for the meta tag.
     *
     */
    protected static function getTitle(Model $model) : string
    {
        $value = match (get_class($model)) {
            Tip::class  => $model->title,
            User::class => $model->name,
        };

        return Str::limit($value, 67);
    }

    /**
     * Retrieve the type to use for the meta tag.
     *
     */
    protected static function getType(Model $model) : string
    {
        return match (get_class($model)) {
            Tip::class  => 'article',
            User::class => 'profile',
        };
    }

    /**
     * Retrieve the url to use for the meta tag.
     *
     */
    protected static function getUrl(Model $model) : string
    {
        return match (get_class($model)) {
            Tip::class  => route('tips.show', ['tip' => $model, 'slug' => $model->slug]),
            User::class => route('profile', ['user' => $model->handle]),
        };
    }
}
