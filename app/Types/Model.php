<?php

namespace App\Types;

use DateTimeInterface;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    /**
     * Attributes which cannot be mass-assigned.
     *
     */
    protected $guarded = [];

    /**
     * Attributes to hide when casting to an array or json.
     *
     */
    protected $hidden = [];

    /**
     * Retrieve a subset of the model's attributes.
     *
     */
    public function except(array $attributes) : array
    {
        return Arr::except($this->getAttributes(), $attributes);
    }

    /**
     * Create a new factory instance for the model.
     *
     */
    public static function factory($count = null, $state = []) : Factory
    {
        $factory = Factory::factoryForModel(get_called_class());

        return $factory
            ->count(is_numeric($count) ? $count : null)
            ->state(is_callable($count) || is_array($count) ? $count : $state);
    }

    /**
     * Determine if the given model belongs to the model.
     *
     */
    public function owns(Model $model, string $key = '') : bool
    {
        $key = $key ?: $this->getForeignKey();

        return $this->getKey() === $model->{$key};
    }

    /**
     * Prepare a date for array or JSON serialization.
     *
     */
    protected function serializeDate(DateTimeInterface $date) : string
    {
        return $date->format('Y-m-d\TH:i:s.000\Z');
    }
}
