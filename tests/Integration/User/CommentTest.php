<?php

namespace Tests\Integration\User;

use App\Models\Tip;
use App\Models\User;
use App\Models\Comment;
use App\Types\ServerTest;
use Illuminate\Support\Str;
use App\Models\Notification;
use App\Enums\NotificationType;
use App\Notifications\CommentReceivedNotification;
use Illuminate\Support\Facades\Notification as NotificationFacade;

class CommentTest extends ServerTest
{
    /** @test */
    public function a_user_can_store_a_comment_and_the_author_does_not_receive_an_email_when_notifications_are_disabled() : void
    {
        NotificationFacade::fake();

        $teacher = User::factory()->create();
        $student = User::factory()->create();

        $tip = Tip::factory()
            ->belongsTo($teacher)
            ->create();

        $payload = [
            'message' => fake()->text(100),
        ];

        $this->actingAs($student)
            ->postJson(route('comments.store', ['tip' => $tip]), $payload)
            ->assertRedirect(route('tips.show', ['tip' => $tip]))
            ->assertNotification('The comment has been added');

        $this->assertCount(1, Comment::get());
        $this->assertCount(1, Notification::get());

        $this->assertTrue(Comment::first()->user->is($student));
        $this->assertTrue(Comment::first()->tip->is($tip));

        $this->assertNull(Comment::first()->parent_id);
        $this->assertEquals(Comment::first()->message, $payload['message']);

        $this->assertTrue(Notification::first()->teacher->is($teacher));
        $this->assertTrue(Notification::first()->student->is($student));
        $this->assertTrue(Notification::first()->tip->is($tip));

        $this->assertNull(Notification::first()->read_at);
        $this->assertEquals(Notification::first()->message, $payload['message']);
        $this->assertEquals(Notification::first()->type, NotificationType::COMMENT);

        NotificationFacade::assertNothingSent();
    }

    /** @test */
    public function a_user_can_store_a_comment_and_the_author_does_receive_an_email_when_notifications_are_enabled() : void
    {
        NotificationFacade::fake();

        $teacher = User::factory()->create([
            'settings' => ['notifications_email_comments' => true],
        ]);

        $student = User::factory()->create();

        $tip = Tip::factory()
            ->belongsTo($teacher)
            ->create();

        $payload = [
            'message' => fake()->text(100),
        ];

        $this->actingAs($student)
            ->postJson(route('comments.store', ['tip' => $tip]), $payload)
            ->assertRedirect(route('tips.show', ['tip' => $tip]))
            ->assertNotification('The comment has been added');

        $this->assertCount(1, Comment::get());
        $this->assertCount(1, Notification::get());

        $this->assertTrue(Comment::first()->user->is($student));
        $this->assertTrue(Comment::first()->tip->is($tip));

        $this->assertNull(Comment::first()->parent_id);
        $this->assertEquals(Comment::first()->message, $payload['message']);

        $this->assertTrue(Notification::first()->teacher->is($teacher));
        $this->assertTrue(Notification::first()->student->is($student));
        $this->assertTrue(Notification::first()->tip->is($tip));

        $this->assertNull(Notification::first()->read_at);
        $this->assertEquals(Notification::first()->message, $payload['message']);
        $this->assertEquals(Notification::first()->type, NotificationType::COMMENT);

        NotificationFacade::assertSent($tip->user, CommentReceivedNotification::class)
            ->assertSubject('New Comment - ' . Str::limit($tip->title, 40))
            ->assertMarkdown('notification.comment')
            ->assertMessageContains($tip->title)
            ->assertMessageContains($student->name)
            ->assertMessageContains($payload['message'])
            ->assertAction('Reply', route('tips.show', ['tip' => $tip]));
    }

    /** @test */
    public function a_user_can_store_a_comment_but_they_are_not_notified_if_they_are_the_author() : void
    {
        NotificationFacade::fake();

        $user = User::factory()->create();

        $tip = Tip::factory()
            ->belongsTo($user)
            ->create();

        $payload = [
            'message' => fake()->text(100),
        ];

        $this->actingAs($user)
            ->postJson(route('comments.store', ['tip' => $tip]), $payload)
            ->assertRedirect(route('tips.show', ['tip' => $tip]))
            ->assertNotification('The comment has been added');

        $this->assertCount(1, Comment::get());
        $this->assertCount(0, Notification::get());

        NotificationFacade::assertNothingSent();
    }

    /** @test */
    public function a_user_cannot_store_a_comment_if_the_tip_is_not_published() : void
    {
        $teacher = User::factory()->create();
        $student = User::factory()->create();

        $tip = Tip::factory()
            ->belongsTo($teacher)
            ->create(['published_at' => now()->addDay()]);

        $payload = [
            'message' => fake()->text(100),
        ];

        $this->actingAs($student)
            ->postJson(route('comments.store', ['tip' => $tip]), $payload)
            ->assertForbidden();
    }

    /** @test */
    public function a_user_can_reply_to_a_comment_and_the_author_does_not_receive_an_email_when_notifications_are_disabled() : void
    {
        NotificationFacade::fake();

        $teacher = User::factory()->create();
        $student = User::factory()->create();

        $tip = Tip::factory()
            ->belongsTo($teacher)
            ->create();

        $comment = Comment::factory()
            ->belongsTo($student, $tip)
            ->create();

        $payload = [
            'message' => fake()->text(100),
        ];

        $this->actingAs($teacher)
            ->postJson(route('comments.reply', ['comment' => $comment]), $payload)
            ->assertRedirect(route('tips.show', ['tip' => $tip]))
            ->assertNotification('The comment has been added');

        $this->assertCount(2, Comment::get());
        $this->assertCount(1, Notification::get());

        $this->assertTrue(Comment::first()->user->is($student));
        $this->assertTrue(Comment::first()->tip->is($tip));

        $this->assertNull(Comment::first()->parent_id);
        $this->assertEquals(Comment::first()->message, $comment->message);

        $this->assertTrue(Comment::second()->user->is($teacher));
        $this->assertTrue(Comment::second()->tip->is($tip));

        $this->assertEquals(Comment::second()->parent_id, $comment->id);
        $this->assertEquals(Comment::second()->message, $payload['message']);

        $this->assertTrue(Notification::first()->teacher->is($student));
        $this->assertTrue(Notification::first()->student->is($teacher));
        $this->assertTrue(Notification::first()->tip->is($tip));

        $this->assertNull(Notification::first()->read_at);
        $this->assertEquals(Notification::first()->message, $payload['message']);
        $this->assertEquals(Notification::first()->type, NotificationType::COMMENT);

        NotificationFacade::assertNothingSent();
    }

    /** @test */
    public function a_user_can_reply_to_a_comment_and_the_author_does_receive_an_email_when_notifications_are_enabled() : void
    {
        NotificationFacade::fake();

        $teacher = User::factory()->create();

        $student = User::factory()->create([
            'settings' => ['notifications_email_comments' => true],
        ]);

        $tip = Tip::factory()
            ->belongsTo($teacher)
            ->create();

        $comment = Comment::factory()
            ->belongsTo($student, $tip)
            ->create();

        $payload = [
            'message' => fake()->text(100),
        ];

        $this->actingAs($teacher)
            ->postJson(route('comments.reply', ['comment' => $comment]), $payload)
            ->assertRedirect(route('tips.show', ['tip' => $tip]))
            ->assertNotification('The comment has been added');

        $this->assertCount(2, Comment::get());
        $this->assertCount(1, Notification::get());

        $this->assertTrue(Comment::first()->user->is($student));
        $this->assertTrue(Comment::first()->tip->is($tip));

        $this->assertNull(Comment::first()->parent_id);
        $this->assertEquals(Comment::first()->message, $comment->message);

        $this->assertTrue(Comment::second()->user->is($teacher));
        $this->assertTrue(Comment::second()->tip->is($tip));

        $this->assertEquals(Comment::second()->parent_id, $comment->id);
        $this->assertEquals(Comment::second()->message, $payload['message']);

        $this->assertTrue(Notification::first()->teacher->is($student));
        $this->assertTrue(Notification::first()->student->is($teacher));
        $this->assertTrue(Notification::first()->tip->is($tip));

        $this->assertNull(Notification::first()->read_at);
        $this->assertEquals(Notification::first()->message, $payload['message']);
        $this->assertEquals(Notification::first()->type, NotificationType::COMMENT);

        NotificationFacade::assertSent($student, CommentReceivedNotification::class)
            ->assertSubject('New Comment - ' . Str::limit($tip->title, 40))
            ->assertMarkdown('notification.comment')
            ->assertMessageContains($tip->title)
            ->assertMessageContains($teacher->name)
            ->assertMessageContains($payload['message'])
            ->assertAction('Reply', route('tips.show', ['tip' => $tip]));
    }

    /** @test */
    public function a_user_cannot_reply_to_their_own_comment() : void
    {
        $teacher = User::factory()->create();
        $student = User::factory()->create();

        $tip = Tip::factory()
            ->belongsTo($teacher)
            ->create();

        $comment = Comment::factory()
            ->belongsTo($student, $tip)
            ->create();

        $payload = [
            'message' => fake()->text(100),
        ];

        $this->actingAs($student)
            ->postJson(route('comments.reply', ['comment' => $comment]), $payload)
            ->assertForbidden();
    }

    /** @test */
    public function a_user_can_update_a_comment() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()->create();

        $comment = Comment::factory()
            ->belongsTo($user, $tip)
            ->create();

        $payload = [
            'message' => fake()->text(100),
        ];

        $this->actingAs($user)
            ->patchJson(route('comments.update', ['comment' => $comment]), $payload)
            ->assertRedirect(route('tips.show', ['tip' => $tip]))
            ->assertNotification('The comment has been updated');

        $this->assertEquals($comment->fresh()->message, $payload['message']);
    }

    /** @test */
    public function a_user_can_delete_a_comment() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()->create();

        $comment = Comment::factory()
            ->belongsTo($user, $tip)
            ->create();

        $this->actingAs($user)
            ->deleteJson(route('comments.delete', ['comment' => $comment]))
            ->assertRedirect(route('tips.show', ['tip' => $tip]))
            ->assertNotification('The comment has been deleted');

        $this->assertCount(0, Comment::get());
    }
}
