<?php

namespace Tests\Integration\Site;

use App\Types\ServerTest;

class LegalTest extends ServerTest
{
    /** @test */
    public function a_user_can_view_the_legal_page() : void
    {
        $this->get(route('legal'))
            ->assertSuccessful()
            ->assertPage('legal.index');
    }
}
