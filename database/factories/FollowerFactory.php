<?php

namespace Database\Factories;

use App\Models\User;
use App\Types\Factory;

class FollowerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     */
    public function definition() : array
    {
        return [
            'teacher_id' => User::factory(),
            'student_id' => User::factory(),
        ];
    }
}
