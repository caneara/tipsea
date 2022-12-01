<?php

namespace Tests\Integration\User;

use App\Models\User;
use App\Types\ServerTest;

class SettingsTest extends ServerTest
{
    /** @test */
    public function a_user_can_store_their_settings() : void
    {
        $user = User::factory()->create();

        $payload = [
            'notifications_email_comments' => true,
        ];

        $this->actingAs($user)
            ->patchJson(route('settings.update'), $payload)
            ->assertRedirect(route('account'))
            ->assertNotification('Your account has been updated');

        $this->assertEquals(
            $payload['notifications_email_comments'],
            $user->fresh()->settings['notifications_email_comments']
        );
    }

    /** @test */
    public function a_user_can_update_their_settings() : void
    {
        $user = User::factory()->create([
            'settings' => [
                'notifications_email_comments' => false,
            ],
        ]);

        $payload = [
            'notifications_email_comments' => true,
        ];

        $this->actingAs($user)
            ->patchJson(route('settings.update'), $payload)
            ->assertRedirect(route('account'))
            ->assertNotification('Your account has been updated');

        $this->assertEquals(
            $payload['notifications_email_comments'],
            $user->fresh()->settings['notifications_email_comments']
        );
    }
}
