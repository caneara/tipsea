<?php

namespace Database\Factories;

use App\Models\Tip;
use App\Models\User;
use App\Types\Factory;
use Illuminate\Support\Arr;
use App\Enums\NotificationType;

class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     */
    public function definition() : array
    {
        return [
            'teacher_id' => User::factory()->create(),
            'student_id' => User::factory()->create(),
            'tip_id'     => Tip::factory()->create(),
            'type'       => $type = Arr::random([NotificationType::LIKE, NotificationType::COMMENT]),
            'message'    => $type === NotificationType::COMMENT ? fake()->text(100) : null,
            'read_at'    => now(),
        ];
    }
}
