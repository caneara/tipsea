<?php

namespace Database\Factories;

use App\Models\Tip;
use App\Models\User;
use App\Models\Banner;
use App\Types\Factory;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class TipFactory extends Factory
{
    /**
     * A selection of possible tag names.
     *
     */
    public static array $tags = [
        'PHP', 'Python', 'JavaScript', 'C#', 'Laravel',
        'Node', 'Vue', 'React', 'MySQL', 'Postgres',
        'Visual Basic', 'F#', 'Svelte', 'Angular',
    ];

    /**
     * Configure the model factory.
     *
     */
    public function configure() : static
    {
        return $this->afterCreating(function(Tip $tip) {
            return tap($tip)->update(['slug' => "{$tip->id}-{$tip->slug}"]);
        });
    }

    /**
     * Define the model's default state.
     *
     */
    public function definition() : array
    {
        return [
            'user_id'      => User::factory(),
            'banner_id'    => Banner::factory(),
            'title'        => $title = fake()->text(50),
            'slug'         => Str::slug($title),
            'summary'      => fake()->text(150),
            'teaser'       => fake()->text(500),
            'theme'        => fake()->randomElement(['light', 'dark']),
            'gradient'     => fake()->numberBetween(1, 12),
            'card'         => uuid(),
            'first_tag'    => ($tags = Arr::random(static::$tags, 4))[0],
            'second_tag'   => $tags[1],
            'third_tag'    => $tags[2],
            'fourth_tag'   => $tags[3],
            'content'      => $this->generateContent(),
            'attribution'  => fake()->boolean() ? 'https://' . fake()->domainName() : null,
            'shared'       => true,
            'published_at' => now()->subDays(rand(1, 100)),
        ];
    }

    /**
     * Create a random block of text for the tip.
     *
     */
    protected function generateContent() : string
    {
        return Str::of(fake()->text(200))
            ->append("\n\n```js\n")
            ->append("let value = '" . Str::random(20) . "';")
            ->append("\n```\n\n")
            ->append(fake()->text(200))
            ->toString();
    }
}
