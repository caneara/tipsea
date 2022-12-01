<?php

namespace Database\Factories;

use App\Models\User;
use App\Types\Factory;

class BannerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     */
    public function definition() : array
    {
        return [
            'user_id' => User::factory(),
            'name'    => fake()->text(20),
            'url'     => 'https://' . fake()->domainName(),
            'graphic' => uuid(),
        ];
    }
}
