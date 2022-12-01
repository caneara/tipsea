<?php

namespace Database\Factories;

use App\Models\Tip;
use App\Models\User;
use App\Types\Factory;

class LikeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     */
    public function definition() : array
    {
        return [
            'tip_id'  => Tip::factory(),
            'user_id' => User::factory(),
        ];
    }
}
