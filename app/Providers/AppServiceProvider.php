<?php

namespace App\Providers;

use App\Macros\Builder;
use App\Support\Carbon;
use App\Macros\Notification;
use App\Macros\TestResponse;
use App\Macros\RedirectResponse;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     */
    public function boot() : void
    {
        Builder::macros();
        Notification::macros();
        TestResponse::macros();
        RedirectResponse::macros();

        Password::defaults(fn() => $this->security());
    }

    /**
     * Register any application services.
     *
     */
    public function register() : void
    {
        Carbon::useImmutable();
    }

    /**
     * Retrieve the default security protections required for passwords.
     *
     */
    protected function security() : Password
    {
        return Password::min(12)
            ->letters()
            ->numbers()
            ->symbols()
            ->mixedCase()
            ->uncompromised();
    }
}
