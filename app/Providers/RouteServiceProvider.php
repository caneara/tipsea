<?php

namespace App\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * The route configuration for the application.
     *
     */
    protected array $setup = [
        'web' => [
            'Authentication' => true,
            'Site'           => true,
            'Storage'        => false,
            'User'           => true,
        ],
    ];

    /**
     * Define the routes for the application.
     *
     */
    public function map() : void
    {
        foreach ($this->setup as $middleware => $routes) {
            foreach ($this->restrict($routes) as $file) {
                Route::middleware($middleware)
                    ->namespace("App\Controllers\\{$file}")
                    ->group(base_path("routes/{$file}.php"));
            }
        };
    }

    /**
     * Exclude the relevant route files based on the current environment.
     *
     */
    protected function restrict(array $routes) : Collection
    {
        return collect($routes)
            ->filter(fn($item) => app()->isProduction() ? $item : true)
            ->keys();
    }
}
