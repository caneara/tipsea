<?php

namespace Tests\Integration\Site;

use App\Types\ServerTest;

class HomeTest extends ServerTest
{
    /** @test */
    public function a_user_can_view_the_home_page() : void
    {
        $this->get(route('home'))
            ->assertSuccessful()
            ->assertPage('home.index');
    }
}
