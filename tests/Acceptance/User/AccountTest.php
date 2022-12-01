<?php

namespace Tests\Acceptance\User;

use App\Models\User;
use App\Types\Browser;
use App\Types\ClientTest;

class AccountTest extends ClientTest
{
    /** @test */
    public function a_user_can_update_the_general_section_of_their_profile() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $payload = User::factory()->make(['email' => $user->email]);

            $browser->loginAs($user)
                ->visitRoute('account')
                ->assertTitle('Account')
                ->assertSee('Account');

            $browser->switchTab('profile');

            $browser->assertSee('General');

            $browser->assertInputValue('name', $user->name)
                ->assertInputValue('handle', $user->handle)
                ->assertInputValue('email', $user->email)
                ->assertInputValue('biography', $user->biography);

            $browser->type('name', $payload->name)
                ->type('handle', $payload->handle)
                ->type('email', $payload->email)
                ->type('biography', $payload->biography)
                ->push('save-general');

            $browser->assertRouteIs('account')
                ->assertTitle('Account')
                ->assertSee('Account');

            $browser->assertSee('Your account has been updated');

            $browser->assertInputValue('name', $payload->name)
                ->assertInputValue('handle', $payload->handle)
                ->assertInputValue('email', $payload->email)
                ->assertInputValue('biography', $payload->biography);
        });
    }

    /** @test */
    public function a_user_can_update_the_internet_section_of_their_profile() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $payload = User::factory()->make();

            $browser->loginAs($user)
                ->visitRoute('account')
                ->assertTitle('Account')
                ->assertSee('Account');

            $browser->switchTab('profile');

            $browser->assertSee('Internet');

            $browser->assertInputValue('website', $user->website)
                ->assertInputValue('donate', $user->donate)
                ->assertInputValue('github', $user->github)
                ->assertInputValue('twitter', $user->twitter)
                ->assertInputValue('linkedin', $user->linkedin)
                ->assertInputValue('youtube', $user->youtube)
                ->assertInputValue('facebook', $user->facebook);

            $browser->type('website', $payload->website)
                ->type('donate', $payload->donate)
                ->type('github', $payload->github)
                ->type('twitter', $payload->twitter)
                ->type('linkedin', $payload->linkedin)
                ->type('youtube', $payload->youtube)
                ->type('facebook', $payload->facebook)
                ->push('save-internet');

            $browser->assertRouteIs('account')
                ->assertTitle('Account')
                ->assertSee('Account');

            $browser->assertSee('Your account has been updated');

            $browser->assertInputValue('website', $payload->website)
                ->assertInputValue('donate', $payload->donate)
                ->assertInputValue('github', $payload->github)
                ->assertInputValue('twitter', $payload->twitter)
                ->assertInputValue('linkedin', $payload->linkedin)
                ->assertInputValue('youtube', $payload->youtube)
                ->assertInputValue('facebook', $payload->facebook);
        });
    }

    /** @test */
    public function a_user_can_delete_their_account() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $browser->loginAs($user)
                ->visitRoute('account')
                ->assertTitle('Account')
                ->assertSee('Account');

            $browser->switchTab('delete');

            $browser->assertSee('Delete your account');

            $browser->type('password', 'Q5p@4xFvw9w#')
                ->push('delete');

            $browser->assertRouteIs('home')
                ->assertTitle('TipSea')
                ->assertSee('TipSea');

            $browser->assertSee('Your account has been deleted');
        });
    }
}
