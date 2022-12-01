<?php

namespace App\Support;

use Carbon\CarbonImmutable;
use Illuminate\Support\Str;
use Carbon\Carbon as BaseCarbon;
use Illuminate\Support\Facades\Date;

class Carbon extends CarbonImmutable
{
    /**
     * Format the instance as a human-friendly relative-time difference.
     *
     */
    public function age() : string
    {
        $zone = env('DUSK_TIME_ZONE', config('app.timezone'));

        $text = $this->setTimezone($zone)->diffForHumans();

        if (! Str::endsWith($text, 'from now')) {
            return $text;
        }

        return Str::of($text)->replace(' from now', '')->prepend('in ')->toString();
    }

    /**
     * Format the instance as a human-friendly date.
     *
     */
    public function date() : string
    {
        return $this->format('M j, Y');
    }

    /**
     * Format the instance as a human-friendly date and time.
     *
     */
    public function dateTime() : string
    {
        $zone = env('DUSK_TIME_ZONE', config('app.timezone'));

        return $this->setTimezone($zone)->format('M j, Y - H:i');
    }

    /**
     * Lock the current date and time.
     *
     */
    public static function freeze() : void
    {
        parent::setTestNow(now()->startOfSecond());
        BaseCarbon::setTestNow(now()->startOfSecond());
    }

    /**
     * Ensure that Laravel always uses an immutable Carbon instance.
     *
     */
    public static function useImmutable() : void
    {
        Date::use(static::class);
    }

    /**
     * Format the instance as a human-friendly time.
     *
     */
    public function time() : string
    {
        $zone = env('DUSK_TIME_ZONE', config('app.timezone'));

        return $this->setTimezone($zone)->format('H:i');
    }
}
