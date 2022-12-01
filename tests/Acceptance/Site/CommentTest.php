<?php

namespace Tests\Acceptance\Site;

use App\Models\Tip;
use App\Models\User;
use App\Types\Browser;
use App\Models\Comment;
use App\Types\ClientTest;

class CommentTest extends ClientTest
{
    /** @test */
    public function a_user_can_store_a_comment() : void
    {
        $this->browse(function(Browser $browser) {
            $student = User::factory()->create();
            $teacher = User::factory()->create();

            $tip = Tip::factory()
                ->belongsTo($teacher)
                ->create();

            $message = fake()->text(100);

            $browser->loginAs($student)
                ->visitRoute('tips.show', ['tip' => $tip])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->push('create');

            $browser->assertSee('Add a new comment');

            $browser->type('message', $message)
                ->push('continue');

            $browser->assertRouteIs('tips.show', ['tip' => $tip])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->assertSee('The comment has been added');

            $browser->assertSee($student->name)
                ->assertSee($message);
        });
    }

    /** @test */
    public function a_user_can_reply_to_a_comment() : void
    {
        $this->browse(function(Browser $browser) {
            $student = User::factory()->create();
            $teacher = User::factory()->create();

            $tip = Tip::factory()
                ->belongsTo($teacher)
                ->create();

            $comment = Comment::factory()
                ->belongsTo($student, $tip)
                ->create();

            $message = fake()->text(100);

            $browser->loginAs($teacher)
                ->visitRoute('tips.show', ['tip' => $tip])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->menu('comment', "reply-{$comment->id}");

            $browser->assertSee('Reply to a comment');

            $browser->type('message', $message)
                ->push('continue');

            $browser->assertRouteIs('tips.show', ['tip' => $tip])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->assertSee('The comment has been added');

            $browser->assertSee($message);
        });
    }

    /** @test */
    public function a_user_can_update_a_comment() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip = Tip::factory()
                ->belongsTo($user)
                ->create();

            $comment = Comment::factory()
                ->belongsTo($user, $tip)
                ->create();

            $message = fake()->text(100);

            $browser->loginAs($user)
                ->visitRoute('tips.show', ['tip' => $tip])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->menu('comment', "edit-{$comment->id}");

            $browser->assertSee('Edit an existing comment')
                ->assertInputValue('message', $comment->message);

            $browser->type('message', $message)
                ->push('continue');

            $browser->assertRouteIs('tips.show', ['tip' => $tip])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->assertSee('The comment has been updated');

            $browser->assertSee($message);
        });
    }

    /** @test */
    public function a_user_can_delete_a_comment() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $tip = Tip::factory()
                ->belongsTo($user)
                ->create();

            $comment = Comment::factory()
                ->belongsTo($user, $tip)
                ->create();

            $browser->loginAs($user)
                ->visitRoute('tips.show', ['tip' => $tip])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->assertSee($comment->message);

            $browser->menu('comment', "delete-{$comment->id}");

            $browser->assertSee('Are you sure you wish to proceed?')
                ->confirm();

            $browser->assertRouteIs('tips.show', ['tip' => $tip])
                ->assertTitle($tip->title)
                ->assertSee($tip->title);

            $browser->assertSee('The comment has been deleted');

            $browser->assertDontSee($comment->message);
        });
    }
}
