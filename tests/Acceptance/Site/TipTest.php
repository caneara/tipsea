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

class TipTest extends ClientTest
{
    /** @test */
    public function a_user_can_show_a_tip() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip = Tip::factory()
                ->belongsTo($user)
                ->create([
                    'content'      => fake()->text(100),
                    'attribution'  => 'https://example.com',
                    'published_at' => now()->subMonths(13),
                ]);

            $related_1 = Tip::factory()->create([
                'first_tag'    => fake()->text(10),
                'content'      => fake()->text(100),
                'published_at' => now()->subMonths(13),
            ]);

            $related_2 = Tip::factory()->create([
                'first_tag'    => $tip->first_tag,
                'content'      => fake()->text(100),
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
                ->visitRoute('tips.show', ['tip' => $tip])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->assertSee($user->name)
                ->assertSee(Str::upper('Donate To Author'));

            $browser->assertSee($tip->title)
                ->assertSee($tip->published_at->age())
                ->assertSee('1 minute read')
                ->assertSee('This tip is a repost')
                ->assertSee($tip->attribution)
                ->assertSee($tip->content)
                ->assertSee(Str::upper($tip->first_tag))
                ->assertSee(Str::upper($tip->second_tag))
                ->assertSee(Str::upper($tip->third_tag))
                ->assertSee(Str::upper($tip->fourth_tag));

            $browser->assertSeeIn("@metrics_likes_{$tip->id}", '2')
                ->assertSeeIn("@metrics_comments_{$tip->id}", '1');

            $browser->assertDontSee($related_1->title);

            $browser->assertSee($related_2->title)
                ->assertSee($related_2->user->name)
                ->assertSee($related_2->summary)
                ->assertSee(Str::upper($related_2->first_tag))
                ->assertSee(Str::upper($related_2->second_tag))
                ->assertSee(Str::upper($related_2->third_tag))
                ->assertSee(Str::upper($related_2->fourth_tag));

            $browser->assertSeeIn("@metrics_likes_{$related_2->id}", '0')
                ->assertSeeIn("@metrics_comments_{$related_2->id}", '0');
        });
    }

    /** @test */
    public function a_user_can_delete_the_tip() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip = Tip::factory()
                ->belongsTo($user)
                ->create(['attribution' => null]);

            $browser->loginAs($user)
                ->visitRoute('tips.show', ['tip' => $tip])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->assertDontSee('This tip is a repost');

            $browser->menu('tip', "delete-{$tip->id}")
                ->confirm();

            $browser->assertRouteIs('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->assertSee('The tip has been deleted');

            $browser->assertDontSee($tip->title);
        });
    }

    /** @test */
    public function a_user_can_toggle_whether_they_follow_a_teacher() : void
    {
        $this->browse(function(Browser $browser) {
            $student = User::factory()->create();
            $teacher = User::factory()->create();

            $tip = Tip::factory()
                ->belongsTo($teacher)
                ->create();

            $browser->loginAs($student)
                ->visitRoute('tips.show', ['tip' => $tip])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->menu('tip', "follower-{$tip->id}");

            $browser->assertSee('The user has been followed');

            $browser->menu('tip', "follower-{$tip->id}");

            $browser->assertSee('The user has been unfollowed');
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
                ->create();

            $browser->loginAs($student)
                ->visitRoute('tips.show', ['tip' => $tip])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->menu('tip', "bookmark-{$tip->id}");

            $browser->assertSee('The bookmark has been created');

            $browser->menu('tip', "bookmark-{$tip->id}");

            $browser->assertSee('The bookmark has been deleted');
        });
    }

    /** @test */
    public function a_user_can_toggle_whether_a_tip_is_liked() : void
    {
        $this->browse(function(Browser $browser) {
            $student = User::factory()->create();
            $teacher = User::factory()->create();

            $tip = Tip::factory()
                ->belongsTo($teacher)
                ->create();

            $browser->loginAs($student)
                ->visitRoute('tips.show', ['tip' => $tip])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->menu('tip', "like-{$tip->id}");

            $browser->assertSee('The tip has been liked');

            $browser->menu('tip', "like-{$tip->id}");

            $browser->assertSee('The tip has been unliked');
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
                ->visitRoute('tips.show', ['tip' => $tip])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->menu('tip', "delete-{$tip->id}")
                ->confirm();

            $browser->assertRouteIs('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->assertSee('The tip has been deleted');
        });
    }

    /** @test */
    public function a_user_can_show_a_tip_with_no_embed() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip = Tip::factory()
                ->belongsTo($user)
                ->create([
                    'content'      => fake()->text(100),
                    'attribution'  => 'https://example.com',
                    'published_at' => now()->subMonths(13),
                ]);

            $browser->loginAs($user)
                ->visitRoute('tips.show', ['tip' => $tip, 'no_embed' => true])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->pause(1000)
                ->assertQueryStringMissing('no_embed');
        });
    }
}
