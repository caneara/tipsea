<?php

namespace Tests\Integration\User;

use App\Models\User;
use App\Types\ServerTest;
use App\Models\Notification;

class NotificationTest extends ServerTest
{
    /** @test */
    public function a_user_can_view_the_notifications_page() : void
    {
        $user = User::factory()->create();

        $notification_1 = Notification::factory()->create([
            'read_at' => null,
        ]);

        $notification_2 = Notification::factory()->create([
            'teacher_id' => $user,
            'read_at'    => null,
        ]);

        $notification_3 = Notification::factory()->create([
            'teacher_id' => $user,
            'read_at'    => now(),
        ]);

        $notification_4 = Notification::factory()->create([
            'teacher_id' => $user,
            'read_at'    => null,
        ]);

        $this->assertEquals(3, $user->fresh()->metrics['notifications']);

        $this->actingAs($user)
            ->get(route('notifications'))
            ->assertSuccessful()
            ->assertPage('notifications.index');

        $this->assertNull($notification_1->fresh()->read_at);
        $this->assertEquals(now(), $notification_2->fresh()->read_at);
        $this->assertEquals(now(), $notification_3->fresh()->read_at);
        $this->assertEquals(now(), $notification_4->fresh()->read_at);

        $this->assertEquals(0, $user->fresh()->metrics['notifications']);
    }
}
