<?php

namespace Tests\Integration\Site;

use App\Models\Tip;
use App\Types\ServerTest;

class TipTest extends ServerTest
{
    /** @test */
    public function a_user_can_show_the_tip_page() : void
    {
        $tip = Tip::factory()->create([
            'published_at' => now()->subHour(),
        ]);

        $this->get(route('tips.show', ['tip' => $tip->slug]))
            ->assertSuccessful()
            ->assertPage('tips.show.index');
    }

    /** @test */
    public function a_user_cannot_show_the_tip_page_if_the_associated_tip_is_a_draft() : void
    {
        $tip = Tip::factory()->create([
            'published_at' => null,
        ]);

        $this->get(route('tips.show', ['tip' => $tip->slug]))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_cannot_show_the_tip_page_if_the_associated_tip_has_not_been_published() : void
    {
        $tip = Tip::factory()->create([
            'published_at' => now()->addHour(),
        ]);

        $this->get(route('tips.show', ['tip' => $tip->slug]))
            ->assertForbidden();
    }
}
