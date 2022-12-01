<?php

namespace Database\Factories;

use App\Types\Factory;
use App\Enums\UserType;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     */
    public function definition() : array
    {
        return [
            'type'              => UserType::CUSTOMER,
            'name'              => fake()->name(),
            'handle'            => fake()->unique()->userName(),
            'email'             => fake()->unique()->email(),
            'password'          => 'Q5p@4xFvw9w#',
            'biography'         => fake()->text(400),
            'website'           => 'https://' . fake()->domainName(),
            'donate'            => 'https://' . fake()->domainName(),
            'twitter'           => 'https://twitter.com/' . Str::random(15),
            'github'            => 'https://github.com/' . Str::random(15),
            'linkedin'          => 'https://linkedin.com/' . Str::random(15),
            'youtube'           => 'https://youtube.com/' . Str::random(15),
            'facebook'          => 'https://facebook.com/' . Str::random(15),
            'avatar'            => uuid(),
            'settings'          => [],
            'integration'       => [],
            'remember_token'    => Str::random(10),
            'email_verified_at' => now(),
        ];
    }
}
