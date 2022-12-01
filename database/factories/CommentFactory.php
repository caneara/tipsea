<?php

namespace Database\Factories;

use App\Models\Tip;
use App\Models\User;
use App\Types\Factory;

class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     */
    public function definition() : array
    {
        return [
            'user_id'   => User::factory(),
            'tip_id'    => Tip::factory(),
            'parent_id' => null,
            'message'   => fake()->text(300),
        ];
    }
}
