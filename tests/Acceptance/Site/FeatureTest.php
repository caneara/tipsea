<?php

namespace Tests\Acceptance\Site;

use App\Types\Browser;
use App\Types\ClientTest;

class FeatureTest extends ClientTest
{
    /** @test */
    public function a_user_can_view_the_features_page() : void
    {
        $this->browse(function(Browser $browser) {
            $browser->visitRoute('features')
                ->assertTitle('Features')
                ->assertSee('The experience');
        });
    }
}
