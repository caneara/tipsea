<?php

namespace Tests\Acceptance\User;

use App\Models\Tip;
use App\Models\Like;
use App\Models\User;
use App\Models\Banner;
use App\Types\Browser;
use App\Models\Comment;
use App\Types\ClientTest;
use Illuminate\Support\Str;

class TipTest extends ClientTest
{
    /** @test */
    public function a_user_can_view_the_tips_page() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

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
                ->visitRoute('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

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

            $tip_1 = Tip::factory()
                ->belongsTo($user)
                ->create([
                    'first_tag'  => 'PHP',
                    'second_tag' => null,
                    'third_tag'  => null,
                    'fourth_tag' => null,
                ]);

            $tip_2 = Tip::factory()
                ->belongsTo($user)
                ->create([
                    'first_tag'  => 'Python',
                    'second_tag' => null,
                    'third_tag'  => null,
                    'fourth_tag' => null,
                ]);

            $browser->loginAs($user)
                ->visitRoute('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->assertSee($tip_1->title)
                ->assertSee($tip_2->title);

            $browser->push('search');

            $browser->assertSee('What are you looking for?');

            $browser->type('tag_or_title', 'PHP')
                ->push('continue', 1000);

            $browser->assertSeeIn('@query', Str::upper('PHP'))
                ->assertSeeIn('@filter', Str::upper('Tag'));

            $browser->assertSee($tip_1->title)
                ->assertDontSee($tip_2->title);

            $browser->push('query', 1000);

            $browser->assertSee($tip_1->title)
                ->assertSee($tip_2->title);

            $browser->push('search');

            $browser->assertSee('What are you looking for?');

            $browser->type('tag_or_title', 'Python')
                ->push('continue', 1000);

            $browser->assertSeeIn('@query', Str::upper('Python'))
                ->assertSeeIn('@filter', Str::upper('Tag'));

            $browser->assertDontSee($tip_1->title)
                ->assertSee($tip_2->title);
        });
    }

    /** @test */
    public function a_user_can_search_for_tips_using_a_filter() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip_1 = Tip::factory()
                ->belongsTo($user)
                ->create(['title' => 'Seeding large scale databases']);

            $tip_2 = Tip::factory()
                ->belongsTo($user)
                ->create(['title' => 'Attaching counter triggers to tables']);

            $browser->loginAs($user)
                ->visitRoute('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->assertSee($tip_1->title)
                ->assertSee($tip_2->title);

            $browser->push('search');

            $browser->assertSee('What are you looking for?');

            $browser->type('tag_or_title', 'large scale')
                ->push('continue', 1000);

            $browser->assertSeeIn('@query', Str::upper('large scale'))
                ->assertSeeIn('@filter', Str::upper('Tag'));

            $browser->assertDontSee($tip_1->title)
                ->assertDontSee($tip_2->title);

            $browser->push('filter', 1000);

            $browser->assertSeeIn('@query', Str::upper('large scale'))
                ->assertSeeIn('@filter', Str::upper('Title'));

            $browser->assertSee($tip_1->title)
                ->assertDontSee($tip_2->title);

            $browser->push('query', 1000);

            $browser->push('search');

            $browser->assertSee('What are you looking for?');

            $browser->type('tag_or_title', 'counter triggers')
                ->push('continue', 1000);

            $browser->assertSeeIn('@query', Str::upper('counter triggers'))
                ->assertSeeIn('@filter', Str::upper('Tag'));

            $browser->assertDontSee($tip_1->title)
                ->assertDontSee($tip_2->title);

            $browser->push('filter', 1000);

            $browser->assertSeeIn('@query', Str::upper('counter triggers'))
                ->assertSeeIn('@filter', Str::upper('Title'));

            $browser->assertDontSee($tip_1->title)
                ->assertSee($tip_2->title);
        });
    }

    /** @test */
    public function a_user_can_create_a_tip() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $browser->loginAs($user)
                ->visitRoute('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->push('create');

            $browser->assertRouteIs('tips.create')
                ->assertTitle('Create Tip')
                ->assertSee('Create Tip');
        });
    }

    /** @test */
    public function a_user_can_edit_a_tip() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $tip = Tip::factory()
                ->belongsTo($user, $banner)
                ->create();

            $browser->loginAs($user)
                ->visitRoute('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->assertSee($tip->title);

            $browser->menu('tip', "edit-{$tip->id}");

            $browser->assertRouteIs('tips.edit', ['tip' => $tip])
                ->assertTitle('Edit Tip')
                ->assertSee('Edit Tip');
        });
    }

    /** @test */
    public function a_user_can_store_a_draft_tip() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $tip = Tip::factory()
                ->belongsTo($user, $banner)
                ->make();

            $browser->loginAs($user)
                ->visitRoute('tips.create')
                ->assertTitle('Create Tip')
                ->assertSee('Create Tip');

            $browser->assertSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');

            $browser->type('title', $tip->title)
                ->type('summary', $tip->summary)
                ->tag('tags', $tip->first_tag)
                ->tag('tags', $tip->second_tag)
                ->tag('tags', $tip->third_tag)
                ->tag('tags', $tip->fourth_tag)
                ->select('banner_id', $tip->banner_id)
                ->type('attribution', $tip->attribution)
                ->type('content', $tip->content)
                ->type('teaser', $tip->teaser)
                ->push('card_publication_draft')
                ->check('shared', true)
                ->push('save', 2000);

            $browser->assertRouteIs('tips.edit', ['tip' => Tip::first()])
                ->assertTitle('Edit Tip')
                ->assertSee('Edit Tip');

            $browser->assertSee('The tip has been created');

            $browser->assertSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');

            $browser->assertInputValue('title', $tip->title)
                ->assertInputValue('summary', $tip->summary)
                ->assertSee($tip->first_tag)
                ->assertSee($tip->second_tag)
                ->assertSee($tip->third_tag)
                ->assertSee($tip->fourth_tag)
                ->assertSelected('banner_id', $tip->banner_id)
                ->assertInputValue('attribution', $tip->attribution)
                ->assertInputValue('content', $tip->content)
                ->assertInputValue('teaser', $tip->teaser)
                ->assertChecked('shared', true);

            $browser->visitRoute('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->assertSee($tip->title)
                ->assertSee($tip->user->name)
                ->assertSee($tip->summary)
                ->assertSee(Str::upper($tip->first_tag))
                ->assertSee(Str::upper($tip->second_tag))
                ->assertSee(Str::upper($tip->third_tag))
                ->assertSee(Str::upper($tip->fourth_tag));

            $browser->assertSeeIn('@metrics_likes_' . Tip::first()->id, '0')
                ->assertSeeIn('@metrics_comments_' . Tip::first()->id, '0');
        });
    }

    /** @test */
    public function a_user_can_store_a_released_tip() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $tip = Tip::factory()
                ->belongsTo($user, $banner)
                ->make();

            $browser->loginAs($user)
                ->visitRoute('tips.create')
                ->assertTitle('Create Tip')
                ->assertSee('Create Tip');

            $browser->assertSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');

            $browser->type('title', $tip->title)
                ->type('summary', $tip->summary)
                ->tag('tags', $tip->first_tag)
                ->tag('tags', $tip->second_tag)
                ->tag('tags', $tip->third_tag)
                ->tag('tags', $tip->fourth_tag)
                ->select('banner_id', $tip->banner_id)
                ->type('attribution', $tip->attribution)
                ->type('content', $tip->content)
                ->type('teaser', $tip->teaser)
                ->push('card_publication_release')
                ->push('save', 2000);

            $browser->assertRouteIs('tips.edit', ['tip' => Tip::first()])
                ->assertTitle('Edit Tip')
                ->assertSee('Edit Tip');

            $browser->assertSee('The tip has been created');

            $browser->assertDontSee('Draft')
                ->assertDontSee('Release')
                ->assertDontSee('Schedule');

            $browser->assertInputValue('title', $tip->title)
                ->assertInputValue('summary', $tip->summary)
                ->assertSee($tip->first_tag)
                ->assertSee($tip->second_tag)
                ->assertSee($tip->third_tag)
                ->assertSee($tip->fourth_tag)
                ->assertSelected('banner_id', $tip->banner_id)
                ->assertInputValue('attribution', $tip->attribution)
                ->assertInputValue('content', $tip->content)
                ->assertInputValue('teaser', $tip->teaser);

            $browser->visitRoute('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->assertSee($tip->title)
                ->assertSee($tip->user->name)
                ->assertSee($tip->summary)
                ->assertSee(Str::upper($tip->first_tag))
                ->assertSee(Str::upper($tip->second_tag))
                ->assertSee(Str::upper($tip->third_tag))
                ->assertSee(Str::upper($tip->fourth_tag));

            $browser->assertSeeIn('@metrics_likes_' . Tip::first()->id, '0')
                ->assertSeeIn('@metrics_comments_' . Tip::first()->id, '0');
        });
    }

    /** @test */
    public function a_user_can_store_a_scheduled_tip() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $tip = Tip::factory()
                ->belongsTo($user, $banner)
                ->make(['published_at' => now()->addMonths(13)]);

            $browser->loginAs($user)
                ->visitRoute('tips.create')
                ->assertTitle('Create Tip')
                ->assertSee('Create Tip');

            $browser->assertSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');

            $browser->type('title', $tip->title)
                ->type('summary', $tip->summary)
                ->tag('tags', $tip->first_tag)
                ->tag('tags', $tip->second_tag)
                ->tag('tags', $tip->third_tag)
                ->tag('tags', $tip->fourth_tag)
                ->select('banner_id', $tip->banner_id)
                ->type('attribution', $tip->attribution)
                ->type('content', $tip->content)
                ->type('teaser', $tip->teaser)
                ->push('card_publication_schedule')
                ->dateTime('published_at', $tip->published_at)
                ->push('save', 2000);

            $browser->assertRouteIs('tips.edit', ['tip' => Tip::first()])
                ->assertTitle('Edit Tip')
                ->assertSee('Edit Tip');

            $browser->assertSee('The tip has been created');

            $browser->assertDontSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');

            $browser->assertInputValue('title', $tip->title)
                ->assertInputValue('summary', $tip->summary)
                ->assertSee($tip->first_tag)
                ->assertSee($tip->second_tag)
                ->assertSee($tip->third_tag)
                ->assertSee($tip->fourth_tag)
                ->assertSelected('banner_id', $tip->banner_id)
                ->assertInputValue('attribution', $tip->attribution)
                ->assertInputValue('content', $tip->content)
                ->assertInputValue('teaser', $tip->teaser)
                ->assertDateTime('published_at', Tip::first()->published_at);

            $browser->visitRoute('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->assertSee($tip->title)
                ->assertSee($tip->user->name)
                ->assertSee($tip->summary)
                ->assertSee(Str::upper($tip->first_tag))
                ->assertSee(Str::upper($tip->second_tag))
                ->assertSee(Str::upper($tip->third_tag))
                ->assertSee(Str::upper($tip->fourth_tag));

            $browser->assertSeeIn('@metrics_likes_' . Tip::first()->id, '0')
                ->assertSeeIn('@metrics_comments_' . Tip::first()->id, '0');
        });
    }

    /** @test */
    public function a_user_can_update_a_tip() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner_1 = Banner::factory()
                ->belongsTo($user)
                ->create();

            $banner_2 = Banner::factory()
                ->belongsTo($user)
                ->create();

            $tip = Tip::factory()
                ->belongsTo($user, $banner_1)
                ->create(['shared' => false, 'published_at' => now()->addMonths(13)]);

            $payload = Tip::factory()
                ->belongsTo($user, $banner_2)
                ->make(['shared' => true, 'published_at' => now()->addMonths(14)]);

            $browser->loginAs($user)
                ->visitRoute('tips.edit', ['tip' => $tip])
                ->assertTitle('Edit Tip')
                ->assertSee('Edit Tip');

            $browser->assertDontSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');

            $browser->assertInputValue('title', $tip->title)
                ->assertInputValue('summary', $tip->summary)
                ->assertSee($tip->first_tag)
                ->assertSee($tip->second_tag)
                ->assertSee($tip->third_tag)
                ->assertSee($tip->fourth_tag)
                ->assertDateTime('published_at', $tip->published_at)
                ->assertSelected('banner_id', $tip->banner_id)
                ->assertInputValue('attribution', $tip->attribution)
                ->assertInputValue('content', $tip->content)
                ->assertInputValue('teaser', $tip->teaser)
                ->assertChecked('shared', $tip->shared);

            $browser->type('title', $payload->title)
                ->type('summary', $payload->summary)
                ->tag('tags', $payload->first_tag, true)
                ->tag('tags', $payload->second_tag)
                ->tag('tags', $payload->third_tag)
                ->tag('tags', $payload->fourth_tag)
                ->select('banner_id', $payload->banner_id)
                ->type('attribution', $payload->attribution)
                ->type('content', $payload->content)
                ->type('teaser', $payload->teaser)
                ->dateTime('published_at', $payload->published_at)
                ->check('shared', $payload->shared)
                ->push('save', 2000);

            $browser->assertRouteIs('tips.edit', ['tip' => $tip])
                ->assertTitle('Edit Tip')
                ->assertSee('Edit Tip');

            $browser->assertSee('The tip has been updated');

            $browser->assertDontSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');

            $browser->assertInputValue('title', $payload->title)
                ->assertInputValue('summary', $payload->summary)
                ->assertSee($payload->first_tag)
                ->assertSee($payload->second_tag)
                ->assertSee($payload->third_tag)
                ->assertSee($payload->fourth_tag)
                ->assertDateTime('published_at', $tip->fresh()->published_at)
                ->assertSelected('banner_id', $payload->banner_id)
                ->assertInputValue('attribution', $payload->attribution)
                ->assertInputValue('content', $payload->content)
                ->assertInputValue('teaser', $payload->teaser)
                ->assertChecked('shared', $payload->shared);

            $browser->visitRoute('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->assertSee($payload->title)
                ->assertSee($payload->user->name)
                ->assertSee($payload->summary)
                ->assertSee(Str::upper($payload->first_tag))
                ->assertSee(Str::upper($payload->second_tag))
                ->assertSee(Str::upper($payload->third_tag))
                ->assertSee(Str::upper($payload->fourth_tag));

            $browser->assertSeeIn("@metrics_likes_{$tip->id}", '0')
                ->assertSeeIn("@metrics_comments_{$tip->id}", '0');
        });
    }

    /** @test */
    public function a_user_can_update_a_draft_tip_and_keep_it_a_draft() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $tip = Tip::factory()
                ->belongsTo($user, $banner)
                ->create(['published_at' => null]);

            $browser->loginAs($user)
                ->visitRoute('tips.edit', ['tip' => $tip])
                ->assertTitle('Edit Tip')
                ->assertSee('Edit Tip');

            $browser->assertSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');

            $browser->push('card_publication_draft')
                ->push('save');

            $browser->assertSee('The tip has been updated');

            $browser->assertSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');
        });
    }

    /** @test */
    public function a_user_can_update_a_draft_tip_and_release_it() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $tip = Tip::factory()
                ->belongsTo($user, $banner)
                ->create(['published_at' => null]);

            $browser->loginAs($user)
                ->visitRoute('tips.edit', ['tip' => $tip])
                ->assertTitle('Edit Tip')
                ->assertSee('Edit Tip');

            $browser->assertSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');

            $browser->push('card_publication_release')
                ->push('save');

            $browser->assertSee('The tip has been updated');

            $browser->assertDontSee('Draft')
                ->assertDontSee('Release')
                ->assertDontSee('Schedule');
        });
    }

    /** @test */
    public function a_user_can_update_a_draft_tip_and_schedule_it() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $tip = Tip::factory()
                ->belongsTo($user, $banner)
                ->create(['published_at' => null]);

            $browser->loginAs($user)
                ->visitRoute('tips.edit', ['tip' => $tip])
                ->assertTitle('Edit Tip')
                ->assertSee('Edit Tip');

            $browser->assertSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');

            $browser->push('card_publication_schedule')
                ->dateTime('published_at', now()->addMonths(13))
                ->push('save');

            $browser->assertSee('The tip has been updated');

            $browser->assertDontSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');
        });
    }

    /** @test */
    public function a_user_cannot_update_the_publication_date_for_a_released_tip() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $tip = Tip::factory()
                ->belongsTo($user, $banner)
                ->create(['published_at' => now()->subWeek()]);

            $browser->loginAs($user)
                ->visitRoute('tips.edit', ['tip' => $tip])
                ->assertTitle('Edit Tip')
                ->assertSee('Edit Tip');

            $browser->assertDontSee('Draft')
                ->assertDontSee('Release')
                ->assertDontSee('Schedule');
        });
    }

    /** @test */
    public function a_user_can_update_a_scheduled_tip_and_release_it() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $tip = Tip::factory()
                ->belongsTo($user, $banner)
                ->create(['published_at' => now()->addMonths(13)]);

            $browser->loginAs($user)
                ->visitRoute('tips.edit', ['tip' => $tip])
                ->assertTitle('Edit Tip')
                ->assertSee('Edit Tip');

            $browser->assertDontSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');

            $browser->push('card_publication_release')
                ->push('save');

            $browser->assertSee('The tip has been updated');

            $browser->assertDontSee('Draft')
                ->assertDontSee('Release')
                ->assertDontSee('Schedule');
        });
    }

    /** @test */
    public function a_user_can_update_a_scheduled_tip_and_maintain_its_schedule() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $tip = Tip::factory()
                ->belongsTo($user, $banner)
                ->create(['published_at' => now()->addMonths(13)]);

            $browser->loginAs($user)
                ->visitRoute('tips.edit', ['tip' => $tip])
                ->assertTitle('Edit Tip')
                ->assertSee('Edit Tip');

            $browser->assertDontSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');

            $browser->push('card_publication_schedule')
                ->dateTime('published_at', now()->addMonths(13))
                ->push('save');

            $browser->assertSee('The tip has been updated');

            $browser->assertDontSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');
        });
    }

    /** @test */
    public function a_user_can_update_a_scheduled_tip_and_reschedule_it() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $tip = Tip::factory()
                ->belongsTo($user, $banner)
                ->create(['published_at' => now()->addMonths(13)]);

            $browser->loginAs($user)
                ->visitRoute('tips.edit', ['tip' => $tip])
                ->assertTitle('Edit Tip')
                ->assertSee('Edit Tip');

            $browser->assertDontSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');

            $browser->push('card_publication_schedule')
                ->dateTime('published_at', now()->addMonths(14))
                ->push('save');

            $browser->assertSee('The tip has been updated');

            $browser->assertDontSee('Draft')
                ->assertSee('Release')
                ->assertSee('Schedule');
        });
    }

    /** @test */
    public function a_user_can_delete_a_tip() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip = Tip::factory()
                ->belongsTo($user)
                ->create();

            $browser->loginAs($user)
                ->visitRoute('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->assertSee($tip->title);

            $browser->menu('tip', "delete-{$tip->id}")
                ->confirm();

            $browser->assertRouteIs('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->assertSee('The tip has been deleted');

            $browser->assertDontSee($tip->title);
        });
    }
}
