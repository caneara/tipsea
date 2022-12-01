<?php

namespace Tests\Acceptance\User;

use App\Models\Tip;
use App\Models\Like;
use App\Models\User;
use App\Types\Browser;
use App\Models\Comment;
use App\Models\Follower;
use App\Types\ClientTest;
use Illuminate\Support\Str;

class DashboardTest extends ClientTest
{
    /** @test */
    public function a_user_can_view_the_dashboard_page() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip = Tip::factory()->create([
                'published_at' => now()->subMonths(13),
            ]);

            Like::factory()
                ->count(2)
                ->belongsTo($tip)
                ->create();

            Comment::factory()
                ->belongsTo($tip)
                ->create();

            Follower::factory()->create([
                'student_id' => $user,
                'teacher_id' => $tip->user,
            ]);

            $browser->loginAs($user)
                ->visitRoute('dashboard')
                ->assertTitle('Dashboard')
                ->assertSee('Dashboard');

            $browser->assertSee($tip->title)
                ->assertSee($tip->user->name)
                ->assertSee($tip->summary)
                ->assertSee(Str::upper($tip->first_tag))
                ->assertSee(Str::upper($tip->second_tag))
                ->assertSee(Str::upper($tip->third_tag))
                ->assertSee(Str::upper($tip->fourth_tag));

            $browser->assertSeeIn("@metrics_likes_{$tip->id}", '2')
                ->assertSeeIn("@metrics_comments_{$tip->id}", '1');
        });
    }

    /** @test */
    public function a_user_can_toggle_whether_a_tip_is_bookmarked() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip = Tip::factory()->create();

            Follower::factory()->create([
                'student_id' => $user,
                'teacher_id' => $tip->user,
            ]);

            $browser->loginAs($user)
                ->visitRoute('dashboard')
                ->assertTitle('Dashboard')
                ->assertSee('Dashboard');

            $browser->assertSee($tip->title);

            $browser->menu('tip', "bookmark-{$tip->id}");

            $browser->assertSee('The bookmark has been created');

            $browser->menu('tip', "bookmark-{$tip->id}");

            $browser->assertSee('The bookmark has been deleted');
        });
    }

    /** @test */
    public function a_user_can_stop_following_a_teacher() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip = Tip::factory()->create();

            Follower::factory()->create([
                'student_id' => $user,
                'teacher_id' => $tip->user,
            ]);

            $browser->loginAs($user)
                ->visitRoute('dashboard')
                ->assertTitle('Dashboard')
                ->assertSee('Dashboard');

            $browser->assertSee($tip->title);

            $browser->menu('tip', "follower-{$tip->id}");

            $browser->assertSee('The user has been unfollowed');

            $browser->assertDontSee($tip->title);
        });
    }
}
