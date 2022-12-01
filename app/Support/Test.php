<?php

namespace App\Support;

use App\Types\Browser;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Foundation\Application;
use Illuminate\Contracts\Console\Kernel;

trait Test
{
    /**
     * Create the application.
     *
     */
    public function createApplication() : Application
    {
        $app = require __DIR__ . '/../../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    /**
     * Perform the pre-testing operations.
     *
     */
    protected function setUp() : void
    {
        parent::setUp();

        Carbon::freeze();

        Http::preventStrayRequests();

        config(['waterfall.rest_time' => 0]);

        File::deleteDirectory(storage_path('framework/testing'), true);

        if (! env('APP_DUSK')) {
            $this->withoutVite();

            return;
        }

        File::deleteDirectory(storage_path('framework/dusk/console'), true);
        File::deleteDirectory(storage_path('framework/dusk/screenshots'), true);

        Browser::$storeConsoleLogAt  = storage_path('framework/dusk/console');
        Browser::$storeScreenshotsAt = storage_path('framework/dusk/screenshots');
    }

    /**
     * Perform the post-testing operations.
     *
     */
    protected function tearDown() : void
    {
        File::deleteDirectory(storage_path('framework/testing'), true);

        if (env('APP_DUSK')) {
            File::deleteDirectory(storage_path('framework/dusk/console'), true);
        }

        parent::tearDown();
    }
}
