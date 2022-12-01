<?php

namespace Tests\Integration\User;

use App\Models\Tip;
use App\Models\Like;
use App\Models\User;
use App\Types\ServerTest;
use App\Models\Notification;
use App\Enums\NotificationType;

class LikeTest extends ServerTest
{
    /** @test */
    public function a_user_can_like_a_tip() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()->create();

        $this->actingAs($user)
            ->postJson(route('likes.store', ['tip' => $tip]))
            ->assertExactJson(['done']);

        $this->assertCount(1, Like::get());
        $this->assertCount(1, Notification::get());

        $this->assertTrue(Like::first()->user->is($user));
        $this->assertTrue(Like::first()->tip->is($tip));

        $this->assertTrue(Notification::first()->teacher->is($tip->user));
        $this->assertTrue(Notification::first()->student->is($user));
        $this->assertTrue(Notification::first()->tip->is($tip));

        $this->assertNull(Notification::first()->message);
        $this->assertNull(Notification::first()->read_at);
        $this->assertEquals(Notification::first()->type, NotificationType::LIKE);
    }

    /** @test */
    public function a_user_cannot_like_a_tip_if_they_are_the_author() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()
            ->belongsTo($user)
            ->create();

        $this->actingAs($user)
            ->postJson(route('likes.store', ['tip' => $tip]))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_cannot_like_a_tip_if_it_is_not_published() : void
    {
        $student = User::factory()->create();
        $teacher = User::factory()->create();

        $tip = Tip::factory()
            ->belongsTo($teacher)
            ->create(['published_at' => now()->addDay()]);

        $this->actingAs($student)
            ->postJson(route('likes.store', ['tip' => $tip]))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_cannot_like_a_tip_if_they_have_already_liked_it() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()->create();

        Like::factory()
            ->belongsTo($user, $tip)
            ->create();

        $this->actingAs($user)
            ->postJson(route('likes.store', ['tip' => $tip]))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_can_delete_a_like() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()->create();

        Like::factory()
            ->belongsTo($user, $tip)
            ->create();

        $this->actingAs($user)
            ->deleteJson(route('likes.delete', ['tip' => $tip]))
            ->assertExactJson(['done']);

        $this->assertCount(0, Like::get());
    }

    /** @test */
    public function a_user_cannot_delete_a_like_if_the_like_does_not_exist() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()->create();

        $this->actingAs($user)
            ->deleteJson(route('likes.delete', ['tip' => $tip]))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_can_like_a_tip_but_it_does_not_create_more_than_one_notification() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()->create();

        $this->actingAs($user)
            ->postJson(route('likes.store', ['tip' => $tip]))
            ->assertExactJson(['done']);

        $this->assertCount(1, Like::get());
        $this->assertCount(1, Notification::get());

        $this->actingAs($user)
            ->deleteJson(route('likes.delete', ['tip' => $tip]))
            ->assertExactJson(['done']);

        $this->assertCount(0, Like::get());
        $this->assertCount(1, Notification::get());

        $this->actingAs($user)
            ->postJson(route('likes.store', ['tip' => $tip]))
            ->assertExactJson(['done']);

        $this->assertCount(1, Like::get());
        $this->assertCount(1, Notification::get());
    }
}
