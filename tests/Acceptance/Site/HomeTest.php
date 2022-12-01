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

class HomeTest extends ClientTest
{
    /** @test */
    public function a_user_can_view_the_home_page() : void
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

            $browser->loginAs($user)
                ->visitRoute('home')
                ->assertTitle('TipSea')
                ->assertSee('Discover');

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
    public function a_user_can_search_for_tips_using_a_query() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip_1 = Tip::factory()->create([
                'first_tag'  => 'PHP',
                'second_tag' => null,
                'third_tag'  => null,
                'fourth_tag' => null,
            ]);

            $tip_2 = Tip::factory()->create([
                'first_tag'  => 'Python',
                'second_tag' => null,
                'third_tag'  => null,
                'fourth_tag' => null,
            ]);

            $browser->loginAs($user)
                ->visitRoute('home')
                ->assertTitle('TipSea')
                ->assertSee('Discover');

            $browser->assertSee($tip_1->title)
                ->assertSee($tip_2->title);

            $browser->type('search_query', 'PHP')
                ->keys('@search_query', '{enter}')
                ->pause();

            $browser->assertSee($tip_1->title)
                ->assertDontSee($tip_2->title);

            $browser->type('search_query', 'Python')
                ->keys('@search_query', '{enter}')
                ->pause();

            $browser->assertDontSee($tip_1->title)
                ->assertSee($tip_2->title);
        });
    }

    /** @test */
    public function a_user_can_search_for_tips_using_a_filter() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip_1 = Tip::factory()->create(['title' => 'Seeding large scale databases']);
            $tip_2 = Tip::factory()->create(['title' => 'Attaching counter triggers to tables']);

            $browser->loginAs($user)
                ->visitRoute('home')
                ->assertTitle('TipSea')
                ->assertSee('Discover');

            $browser->assertSee($tip_1->title)
                ->assertSee($tip_2->title);

            $browser->type('search_query', 'large scale')
                ->keys('@search_query', '{enter}')
                ->pause();

            $browser->assertDontSee($tip_1->title)
                ->assertDontSee($tip_2->title);

            $browser->push('search_filter_title', 1000);

            $browser->assertSee($tip_1->title)
                ->assertDontSee($tip_2->title);

            $browser->type('search_query', 'counter triggers')
                ->keys('@search_query', '{enter}')
                ->pause();

            $browser->assertDontSee($tip_1->title)
                ->assertSee($tip_2->title);

            $browser->push('search_filter_tag', 1000);

            $browser->assertDontSee($tip_1->title)
                ->assertDontSee($tip_2->title);
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
                ->visitRoute('home')
                ->assertTitle('TipSea')
                ->assertSee('Discover');

            $browser->assertSee($tip->title);

            $browser->menu('tip', "delete-{$tip->id}")
                ->confirm();

            $browser->assertRouteIs('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->assertSee('The tip has been deleted');

            $browser->visitRoute('home')
                ->assertTitle('TipSea')
                ->assertSee('Discover');

            $browser->assertDontSee($tip->title);
        });
    }
}
