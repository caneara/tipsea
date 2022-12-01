<?php

namespace Tests\Acceptance\User;

use App\Models\User;
use App\Types\Browser;
use App\Types\ClientTest;

class IntegrationTest extends ClientTest
{
    /** @test */
    public function a_user_can_integrate_their_account() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $browser->loginAs($user)
                ->visitRoute('account')
                ->assertTitle('Account')
                ->assertSee('Account');

            $browser->switchTab('settings');

            $browser->assertSee('Integration');

            $browser->push('connect', 5000);

            $browser->assertHostIs('api.twitter.com');
        });
    }
}
