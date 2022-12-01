<?php

namespace Tests\Acceptance\User;

use App\Models\User;
use App\Types\Browser;
use App\Types\ClientTest;
use Illuminate\Support\Str;
use App\Models\Notification;
use App\Enums\NotificationType;

class NotificationTest extends ClientTest
{
    /** @test */
    public function a_user_can_view_the_notifications_page() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $item_1 = Notification::factory()->create([
                'teacher_id' => $user,
                'type'       => NotificationType::COMMENT,
                'message'    => fake()->text(50),
                'read_at'    => now()->subYears(2)->subDays(1),
                'created_at' => now()->subYears(2)->subDays(1),
            ]);

            $item_2 = Notification::factory()->create([
                'teacher_id' => $user,
                'type'       => NotificationType::LIKE,
                'message'    => null,
                'read_at'    => null,
                'created_at' => now()->subYears(2)->subDays(2),
            ]);

            $item_3 = Notification::factory()->create([
                'teacher_id' => $user,
                'type'       => NotificationType::COMMENT,
                'message'    => fake()->text(50),
                'read_at'    => now()->subYears(2)->subDays(2),
                'created_at' => now()->subYears(2)->subDays(3),
            ]);

            $browser->loginAs($user)
                ->visitRoute('notifications')
                ->assertTitle('Notifications')
                ->assertSee('Notifications');

            $browser->within("@notification_{$item_1->id}", function($browser) use ($item_1) {
                $browser->assertSee($item_1->tip->title)
                    ->assertSee($item_1->student->name)
                    ->assertSee($item_1->created_at->age())
                    ->assertSee($item_1->message);
            });

            $browser->within("@notification_{$item_2->id}", function($browser) use ($item_2) {
                $browser->assertSee($item_2->tip->title)
                    ->assertSee($item_2->student->name)
                    ->assertSee($item_2->created_at->age())
                    ->assertSee(Str::upper('New'));
            });

            $browser->within("@notification_{$item_3->id}", function($browser) use ($item_3) {
                $browser->assertSee($item_3->tip->title)
                    ->assertSee($item_3->student->name)
                    ->assertSee($item_3->created_at->age())
                    ->assertSee($item_3->message);
            });
        });
    }
}
