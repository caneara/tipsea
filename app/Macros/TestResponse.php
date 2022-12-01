<?php

namespace App\Macros;

use PHPUnit\Framework\Assert as PHPUnit;
use Illuminate\Testing\TestResponse as Facade;
use Inertia\Testing\AssertableInertia as Assert;

class TestResponse
{
    /**
     * Register the test response macros.
     *
     */
    public static function macros() : void
    {
        static::assertPage();
        static::assertNotification();
    }

    /**
     * Register the 'assert notification' macro.
     *
     */
    protected static function assertNotification() : void
    {
        Facade::macro('assertNotification', function($message, $type = 'success', $fixed = false) {
            return tap($this)->assertSessionHas('notification', compact('message', 'type', 'fixed'));
        });
    }

    /**
     * Register the 'assert page' macro.
     *
     */
    protected static function assertPage() : void
    {
        Facade::macro('assertPage', function($path) {
            $page = str_replace('/', '.', $this->original->getData()['page']['component']);

            PHPUnit::assertEquals($path, $page);

            return $this;
        });
    }
}
