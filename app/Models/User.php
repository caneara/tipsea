<?php

namespace App\Models;

use App\Types\Model;
use App\Casts\HashCast;
use App\Enums\UserType;
use App\Concerns\User\Relationships;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Notifications\RoutesNotifications;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\MustVerifyEmail as MustVerifyEmailContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract, CanResetPasswordContract, MustVerifyEmailContract
{
    use Authorizable;
    use Relationships;
    use Authenticatable;
    use MustVerifyEmail;
    use CanResetPassword;
    use RoutesNotifications;

    /**
     * The attributes that should be hidden for arrays.
     *
     */
    protected $hidden = [
        'type',
        'password',
        'settings',
        'integration',
        'remember_token',
        'email_verified_at',
    ];

    /**
     * The attributes that should be cast to specific types.
     *
     */
    protected $casts = [
        'id'                => 'integer',
        'type'              => UserType::class,
        'name'              => 'string',
        'handle'            => 'string',
        'email'             => 'string',
        'password'          => HashCast::class,
        'biography'         => 'string',
        'website'           => 'string',
        'donate'            => 'string',
        'twitter'           => 'string',
        'github'            => 'string',
        'linkedin'          => 'string',
        'youtube'           => 'string',
        'facebook'          => 'string',
        'avatar'            => 'string',
        'metrics'           => 'array',
        'settings'          => 'array',
        'integration'       => 'encrypted:array',
        'remember_token'    => 'string',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Send the password reset notification.
     *
     */
    public function sendPasswordResetNotification($token) : void
    {
        $this->notify(new ResetPasswordNotification($token));
    }
}
