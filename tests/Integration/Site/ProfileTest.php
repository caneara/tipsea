<?php

namespace Tests\Integration\Site;

use App\Models\User;
use App\Types\ServerTest;

class ProfileTest extends ServerTest
{
    /** @test */
    public function a_user_can_view_the_profile_page() : void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('profile', ['user' => $user->handle]))
            ->assertSuccessful()
            ->assertPage('profile.index');
    }
}
