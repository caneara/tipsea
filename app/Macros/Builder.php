<?php

namespace App\Macros;

use Illuminate\Database\Query\Builder as QueryFacade;
use Illuminate\Database\Eloquent\Builder as EloquentQueryFacade;

class Builder
{
    /**
     * Register the query builder macros.
     *
     */
    public static function macros() : void
    {
        static::third();
        static::second();
        static::toRawSql();
        static::whereLike();
    }

    /**
     * Register the 'second' macro.
     *
     */
    protected static function second() : void
    {
        EloquentQueryFacade::macro('second', function() {
            return $this->orderBy('id')->offset(1)->limit(1)->first();
        });
    }

    /**
     * Register the 'third' macro.
     *
     */
    protected static function third() : void
    {
        EloquentQueryFacade::macro('third', function() {
            return $this->orderBy('id')->offset(2)->limit(1)->first();
        });
    }

    /**
     * Register the 'to raw SQL' macro.
     *
     */
    protected static function toRawSql() : void
    {
        QueryFacade::macro('toRawSql', function() {
            dd(vsprintf(str_replace(['?'], ['\'%s\''], $this->toSql()), $this->getBindings()));
        });
    }

    /**
     * Register the 'where like' macro.
     *
     */
    protected static function whereLike() : void
    {
        QueryFacade::macro('whereLike', function($key, $value = '') {
            $value = preg_replace("/[^[:alnum:][:space:]]/iu", '', $value);

            return $this->when($value, fn($query) => $query->where($key, 'LIKE', "{$value}%"));
        });
    }
}
