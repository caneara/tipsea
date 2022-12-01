<?php

namespace Tests\Integration\User;

use App\Models\Tip;
use App\Models\User;
use App\Models\Bookmark;
use App\Types\ServerTest;

class BookmarkTest extends ServerTest
{
    /** @test */
    public function a_user_can_view_the_bookmarks_page() : void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('bookmarks'))
            ->assertSuccessful()
            ->assertPage('bookmarks.index');
    }

    /** @test */
    public function a_user_can_store_a_bookmark() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()->create();

        $this->actingAs($user)
            ->postJson(route('bookmarks.store', ['tip' => $tip]))
            ->assertExactJson(['done']);

        $this->assertCount(1, Bookmark::get());

        $this->assertTrue(Bookmark::first()->user->is($user));
        $this->assertTrue(Bookmark::first()->tip->is($tip));
    }

    /** @test */
    public function a_user_cannot_store_a_bookmark_if_the_tip_belongs_to_the_user() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()
            ->belongsTo($user)
            ->create();

        $this->actingAs($user)
            ->postJson(route('bookmarks.store', ['tip' => $tip]))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_cannot_store_a_bookmark_if_the_tip_does_not_have_a_publish_date() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()->create(['published_at' => null]);

        $this->actingAs($user)
            ->postJson(route('bookmarks.store', ['tip' => $tip]))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_cannot_store_a_bookmark_if_the_tip_has_not_yet_been_published() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()->create(['published_at' => now()->addHour()]);

        $this->actingAs($user)
            ->postJson(route('bookmarks.store', ['tip' => $tip]))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_cannot_store_a_bookmark_if_they_have_already_bookmarked_the_tip() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()->create();

        Bookmark::factory()
            ->belongsTo($user, $tip)
            ->create();

        $this->actingAs($user)
            ->postJson(route('bookmarks.store', ['tip' => $tip]))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_can_delete_a_bookmark() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()->create();

        Bookmark::factory()
            ->belongsTo($user, $tip)
            ->create();

        $this->actingAs($user)
            ->deleteJson(route('bookmarks.delete', ['tip' => $tip]))
            ->assertExactJson(['done']);

        $this->assertCount(0, Bookmark::get());
    }

    /** @test */
    public function a_user_cannot_delete_a_bookmark_that_does_not_exist() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()->create();

        $this->actingAs($user)
            ->deleteJson(route('bookmarks.delete', ['tip' => $tip]))
            ->assertForbidden();
    }
}
