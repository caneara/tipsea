<?php

namespace Tests\Integration\Site;

use App\Types\ServerTest;

class FeatureTest extends ServerTest
{
    /** @test */
    public function a_user_can_view_the_features_page() : void
    {
        $this->get(route('features'))
            ->assertSuccessful()
            ->assertPage('features.index');
    }
}
