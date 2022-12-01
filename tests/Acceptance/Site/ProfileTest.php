<?php

namespace Tests\Acceptance\Site;

use App\Models\Tip;
use App\Models\Like;
use App\Models\User;
use App\Types\Browser;
use App\Enums\UserType;
use App\Models\Comment;
use App\Types\ClientTest;
use Illuminate\Support\Str;

class ProfileTest extends ClientTest
{
    /** @test */
    public function a_user_can_view_the_profile_page() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create([
                'metrics' => ['followers' => 3],
            ]);

            $tip = Tip::factory()
                ->belongsTo($user)
                ->create([
                    'published_at' => now()->subMonths(13),
                ]);

            Like::factory()
                ->count(2)
                ->belongsTo($tip)
                ->create();

            Comment::factory()
                ->belongsTo($tip)
                ->create();

            $browser->loginAs($user)
                ->visitRoute('profile', ['user' => $user->handle])
                ->assertTitle($user->name)
                ->assertSee($user->name);

            $browser->assertSee($user->name)
                ->assertSee("@{$user->handle}")
                ->assertSee($user->biography)
                ->assertSee(Str::upper('1 code tip'))
                ->assertSee(Str::upper('3 followers'));

            $browser->assertSee($tip->title)
                ->assertSee($user->name)
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
    public function a_user_can_toggle_whether_they_follow_a_teacher() : void
    {
        $this->browse(function(Browser $browser) {
            $student = User::factory()->create();
            $teacher = User::factory()->create();

            $browser->loginAs($student)
                ->visitRoute('profile', ['user' => $teacher->handle])
                ->assertTitle($teacher->name)
                ->assertSee($teacher->name);

            $browser->assertDontSee(Str::upper('Unfollow'));

            $browser->push('follower');

            $browser->assertSee(Str::upper('Unfollow'));

            $browser->push('follower');

            $browser->assertDontSee(Str::upper('Unfollow'));
        });
    }

    /** @test */
    public function a_user_can_toggle_whether_a_tip_is_bookmarked() : void
    {
        $this->browse(function(Browser $browser) {
            $student = User::factory()->create();
            $teacher = User::factory()->create();

            $tip = Tip::factory()
                ->belongsTo($teacher)
                ->create([
                    'published_at' => now()->subMonths(13),
                ]);

            $browser->loginAs($student)
                ->visitRoute('profile', ['user' => $teacher->handle])
                ->assertTitle($teacher->name)
                ->assertSee($teacher->name);

            $browser->menu('tip', "bookmark-{$tip->id}");

            $browser->assertSee('The bookmark has been created');

            $browser->menu('tip', "bookmark-{$tip->id}");

            $browser->assertSee('The bookmark has been deleted');
        });
    }

    /** @test */
    public function a_user_that_is_an_employee_can_delete_a_tip() : void
    {
        $this->browse(function(Browser $browser) {
            $user_1 = User::factory()->create(['type' => UserType::EMPLOYEE]);
            $user_2 = User::factory()->create(['type' => UserType::CUSTOMER]);

            $tip = Tip::factory()
                ->belongsTo($user_2)
                ->create();

            $browser->loginAs($user_1)
                ->visitRoute('profile', ['user' => $user_2->handle])
                ->assertTitle($user_2->name)
                ->assertSee($user_2->name);

            $browser->assertSee($tip->title);

            $browser->menu('tip', "delete-{$tip->id}")
                ->confirm();

            $browser->assertRouteIs('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->assertSee('The tip has been deleted');

            $browser->visitRoute('profile', ['user' => $user_2->handle])
                ->assertTitle($user_2->name)
                ->assertSee($user_2->name);

            $browser->assertDontSee($tip->title);
        });
    }
}
