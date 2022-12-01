<?php

namespace Tests\Acceptance\User;

use App\Models\User;
use App\Types\Browser;
use App\Types\ClientTest;

class SettingsTest extends ClientTest
{
    /** @test */
    public function a_user_can_update_their_settings() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create([
                'settings' => [
                    'notifications_email_comments' => false,
                ],
            ]);

            $payload = User::factory()->make([
                'settings' => [
                    'notifications_email_comments' => true,
                ],
            ]);

            $browser->loginAs($user)
                ->visitRoute('account')
                ->assertTitle('Account')
                ->assertSee('Account');

            $browser->switchTab('settings');

            $browser->assertSee('Notifications');

            $browser->assertChecked('notifications_email_comments', $user->settings['notifications_email_comments']);

            $browser->check('notifications_email_comments', $payload->settings['notifications_email_comments'])
                ->push('update');

            $browser->assertRouteIs('account')
                ->assertTitle('Account')
                ->assertSee('Account');

            $browser->assertSee('Your account has been updated');

            $browser->assertChecked('notifications_email_comments', $payload->settings['notifications_email_comments']);
        });
    }
}
