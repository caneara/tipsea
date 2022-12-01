<?php

namespace App\Providers;

use App\Types\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Bugsnag\BugsnagLaravel\Facades\Bugsnag;
use Illuminate\Support\Traits\Conditionable;
use Illuminate\Database\LazyLoadingViolationException;

class DatabaseServiceProvider extends ServiceProvider
{
    use Conditionable;

    /**
     * Bootstrap any application services.
     *
     */
    public function boot() : void
    {
        Model::preventLazyLoading(! app()->isProduction());

        Model::handleLazyLoadingViolationUsing(function($model, $relation) {
            $this->handleLazyLoading($model, $relation);
        });

        $this->when(app()->isProduction(), fn() => $this->logSlowQueries());
    }

    /**
     * Ensure that application models cannot be lazy-loaded.
     *
     */
    protected function handleLazyLoading(Model $model, string $relation) : void
    {
        $source = get_class($model->$relation()->getRelated());

        if (! app()->runningUnitTests() && Str::startsWith($source, 'App')) {
            throw new LazyLoadingViolationException($model, $relation);
        }
    }

    /**
     * Advise the error-tracking service to record all slow database queries.
     *
     */
    protected function logSlowQueries() : void
    {
        $error   = 'SlowDatabaseQueryError';
        $message = 'The database query did not execute in a timely fashion.';

        DB::whenQueryingForLongerThan(1000, function($source, $event) use ($error, $message) {
            Bugsnag::notifyError($error, $message, function($report) use ($event) {
                return $report->setSeverity('warning')->setMetaData([
                    'query' => [
                        'sql'      => $event->sql,
                        'bindings' => $event->bindings,
                    ],
                ]);
            });
        });
    }
}
