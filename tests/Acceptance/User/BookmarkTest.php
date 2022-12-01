<?php

namespace Tests\Acceptance\User;

use App\Models\Tip;
use App\Models\Like;
use App\Models\User;
use App\Types\Browser;
use App\Models\Comment;
use App\Models\Bookmark;
use App\Types\ClientTest;
use Illuminate\Support\Str;

class BookmarkTest extends ClientTest
{
    /** @test */
    public function a_user_can_view_the_bookmarks_page() : void
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

            Bookmark::factory()
                ->belongsTo($user, $tip)
                ->create();

            $browser->loginAs($user)
                ->visitRoute('bookmarks')
                ->assertTitle('Bookmarks')
                ->assertSee('Bookmarks');

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
    public function a_user_can_search_for_bookmarks_using_a_query() : void
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

            Bookmark::factory()
                ->belongsTo($user, $tip_1)
                ->create();

            Bookmark::factory()
                ->belongsTo($user, $tip_2)
                ->create();

            $browser->loginAs($user)
                ->visitRoute('bookmarks')
                ->assertTitle('Bookmarks')
                ->assertSee('Bookmarks');

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
    public function a_user_can_search_for_bookmarks_using_a_filter() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip_1 = Tip::factory()->create([
                'title' => 'Seeding large scale databases',
            ]);

            $tip_2 = Tip::factory()->create([
                'title' => 'Attaching counter triggers to tables',
            ]);

            Bookmark::factory()
                ->belongsTo($user, $tip_1)
                ->create();

            Bookmark::factory()
                ->belongsTo($user, $tip_2)
                ->create();

            $browser->loginAs($user)
                ->visitRoute('bookmarks')
                ->assertTitle('Bookmarks')
                ->assertSee('Bookmarks');

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
    public function a_user_can_delete_a_bookmark() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip = Tip::factory()->create();

            Bookmark::factory()
                ->belongsTo($user, $tip)
                ->create();

            $browser->loginAs($user)
                ->visitRoute('bookmarks')
                ->assertTitle('Bookmarks')
                ->assertSee('Bookmarks');

            $browser->assertSee($tip->title);

            $browser->menu('tip', "bookmark-{$tip->id}");

            $browser->assertSee('The bookmark has been deleted');

            $browser->assertDontSee($tip->title);
        });
    }

    /** @test */
    public function a_user_can_toggle_whether_they_follow_a_teacher() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip = Tip::factory()->create();

            Bookmark::factory()
                ->belongsTo($user, $tip)
                ->create();

            $browser->loginAs($user)
                ->visitRoute('bookmarks')
                ->assertTitle('Bookmarks')
                ->assertSee('Bookmarks');

            $browser->assertSee($tip->title);

            $browser->menu('tip', "follower-{$tip->id}");

            $browser->assertSee('The user has been followed');

            $browser->menu('tip', "follower-{$tip->id}");

            $browser->assertSee('The user has been unfollowed');
        });
    }
}
