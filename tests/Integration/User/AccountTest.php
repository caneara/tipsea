<?php

namespace Tests\Integration\User;

use App\Models\Tip;
use App\Models\User;
use App\Models\Banner;
use App\Storage\Image;
use App\Types\ServerTest;

class AccountTest extends ServerTest
{
    /** @test */
    public function a_user_can_view_the_account_page() : void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('account'))
            ->assertSuccessful()
            ->assertPage('account.index');
    }

    /** @test */
    public function a_user_can_update_their_account() : void
    {
        $user = User::factory()->create();

        $payload = User::factory()->make(['email' => $user->email]);

        $this->actingAs($user)
            ->patchJson(route('account.update'), $payload->toArray())
            ->assertRedirect(route('account'))
            ->assertNotification('Your account has been updated');

        $this->get(route('account'))
            ->assertSuccessful();

        $this->assertEquals($user->fresh()->name, $payload->name);
        $this->assertEquals($user->fresh()->handle, $payload->handle);
        $this->assertEquals($user->fresh()->email, $payload->email);
        $this->assertEquals($user->fresh()->biography, $payload->biography);
        $this->assertEquals($user->fresh()->website, $payload->website);
        $this->assertEquals($user->fresh()->donate, $payload->donate);
        $this->assertEquals($user->fresh()->github, $payload->github);
        $this->assertEquals($user->fresh()->twitter, $payload->twitter);
        $this->assertEquals($user->fresh()->linkedin, $payload->linkedin);
        $this->assertEquals($user->fresh()->youtube, $payload->youtube);
        $this->assertEquals($user->fresh()->facebook, $payload->facebook);
    }

    /** @test */
    public function a_user_must_verify_their_email_address_if_they_have_changed_it() : void
    {
        $user = User::factory()->create();

        $payload = User::factory()->make();

        $this->actingAs($user)
            ->patchJson(route('account.update'), $payload->toArray())
            ->assertRedirect(route('account'))
            ->assertNotification('Your account has been updated');

        $this->get(route('account'))
            ->assertRedirect(route('email.verify.notice'));

        $this->assertEquals($user->fresh()->name, $payload->name);
        $this->assertEquals($user->fresh()->handle, $payload->handle);
        $this->assertEquals($user->fresh()->email, $payload->email);
        $this->assertEquals($user->fresh()->biography, $payload->biography);
        $this->assertEquals($user->fresh()->website, $payload->website);
        $this->assertEquals($user->fresh()->donate, $payload->donate);
        $this->assertEquals($user->fresh()->github, $payload->github);
        $this->assertEquals($user->fresh()->twitter, $payload->twitter);
        $this->assertEquals($user->fresh()->linkedin, $payload->linkedin);
        $this->assertEquals($user->fresh()->youtube, $payload->youtube);
        $this->assertEquals($user->fresh()->facebook, $payload->facebook);
    }

    /** @test */
    public function a_user_can_delete_their_account() : void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->deleteJson(route('account.delete'), ['password' => 'Q5p@4xFvw9w#'])
            ->assertRedirect(route('home'))
            ->assertNotification('Your account has been deleted');

        $this->assertGuest();

        $this->assertCount(0, User::get());
    }

    /** @test */
    public function it_deletes_images_belonging_to_a_user_when_they_delete_their_account() : void
    {
        $user = User::factory()->create();

        $banner = Banner::factory()
            ->belongsTo($user)
            ->create();

        $tip = Tip::factory()
            ->belongsTo($user, $banner)
            ->create();

        Image::fake($tip->card);
        Image::fake($user->avatar);
        Image::fake($banner->graphic);

        $this->actingAs($user)
            ->deleteJson(route('account.delete'), ['password' => 'Q5p@4xFvw9w#'])
            ->assertRedirect(route('home'))
            ->assertNotification('Your account has been deleted');

        $this->assertGuest();

        $this->assertCount(0, Tip::get());
        $this->assertCount(0, User::get());
        $this->assertCount(0, Banner::get());

        Image::assertMissing($tip->card);
        Image::assertMissing($user->avatar);
        Image::assertMissing($banner->graphic);
    }
}
