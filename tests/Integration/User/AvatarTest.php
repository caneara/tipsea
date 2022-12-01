<?php

namespace Tests\Integration\User;

use App\Models\User;
use App\Storage\Image;
use App\Types\ServerTest;

class AvatarTest extends ServerTest
{
    /** @test */
    public function a_user_can_update_their_avatar() : void
    {
        $user = User::factory()->create(['avatar' => $original = uuid()]);

        Image::fake($original);
        Image::fakeTemporary($id = uuid());

        $this->actingAs($user)
            ->patchJson(route('avatar.update'), ['avatar' => $id])
            ->assertRedirect(route('account'))
            ->assertNotification('Your account has been updated');

        Image::assertMissing($original);
        Image::assertExists($user->fresh()->avatar);
    }
}
