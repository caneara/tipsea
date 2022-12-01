<?php

namespace Tests\Integration\User;

use App\Models\User;
use App\Types\ServerTest;
use Illuminate\Support\Facades\Http;

class IntegrationTest extends ServerTest
{
    /** @test */
    public function a_user_can_create_the_integration() : void
    {
        $user = User::factory()->create();

        Http::fake(fn() => Http::response('oauth_token=1&oauth_token_secret=2'));

        $this->actingAs($user)
            ->get(route('integration.create'))
            ->assertRedirect('https://api.twitter.com/oauth/authorize?oauth_token=1');

        $this->assertEquals('1', cache()->get("integration.{$user->id}.token"));
        $this->assertEquals('2', cache()->get("integration.{$user->id}.secret"));
    }

    /** @test */
    public function a_user_cannot_create_the_integration_if_one_already_exists() : void
    {
        $user = User::factory()->create(['integration' => ['test']]);

        $this->actingAs($user)
            ->get(route('integration.create'))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_can_store_the_integration() : void
    {
        $user = User::factory()->create();

        cache()->put("integration.{$user->id}.token", '1');

        Http::fake(fn() => Http::response('oauth_token=3&oauth_token_secret=4&user_id=5&screen_name=6'));

        $this->actingAs($user)
            ->get(route('integration.store', ['oauth_token' => '1', 'oauth_verifier' => '2']))
            ->assertRedirect(route('account'))
            ->assertNotification('You are now connected');

        $integration = [
            'token'  => '3',
            'secret' => '4',
            'id'     => '5',
            'handle' => '6',
        ];

        $this->assertEquals($integration, $user->fresh()->integration);
    }

    /** @test */
    public function a_user_cannot_store_the_integration_if_one_already_exists() : void
    {
        $user = User::factory()->create(['integration' => ['test']]);

        $this->actingAs($user)
            ->get(route('integration.store', ['oauth_token' => '1', 'oauth_verifier' => '2']))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_cannot_store_the_integration_using_an_invalid_token() : void
    {
        $user = User::factory()->create();

        cache()->put("integration.{$user->id}.token", '1');

        $this->actingAs($user)
            ->get(route('integration.store', ['oauth_token' => '2', 'oauth_verifier' => '3']))
            ->assertInvalid('oauth_token');
    }

    /** @test */
    public function a_user_can_delete_the_integration() : void
    {
        $user = User::factory()->create(['integration' => ['test']]);

        $this->actingAs($user)
            ->deleteJson(route('integration.delete'))
            ->assertRedirect(route('account'))
            ->assertNotification('You are now disconnected');

        $this->assertTrue(blank($user->fresh()->integration));
    }
}
