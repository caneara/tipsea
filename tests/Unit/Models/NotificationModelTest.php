<?php

namespace Tests\Unit\Models;

use App\Types\ServerTest;
use App\Models\Notification;

class NotificationModelTest extends ServerTest
{
    /** @test */
    public function it_prunes_old_notifications() : void
    {
        $notification_1 = Notification::factory()->create(['created_at' => now()->subDays(10)]);
        $notification_2 = Notification::factory()->create(['created_at' => now()->subDays(20)]);
        $notification_3 = Notification::factory()->create(['created_at' => now()->subDays(30)]);
        $notification_4 = Notification::factory()->create(['created_at' => now()->subDays(40)]);

        $this->artisan('model:prune')
            ->assertSuccessful();

        $this->assertCount(2, Notification::get());

        $this->assertTrue(Notification::first()->is($notification_1));
        $this->assertTrue(Notification::second()->is($notification_2));
    }
}
