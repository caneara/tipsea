<?php

namespace Database\Factories;

use App\Models\Tip;
use App\Models\User;
use App\Types\Factory;

class BookmarkFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     */
    public function definition() : array
    {
        return [
            'user_id' => User::factory(),
            'tip_id'  => Tip::factory(),
        ];
    }
}
