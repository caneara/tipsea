<?php

namespace Tests\Acceptance\User;

use App\Models\User;
use App\Storage\Image;
use App\Types\Browser;
use App\Types\ClientTest;
use Illuminate\Support\Facades\File;

class AvatarTest extends ClientTest
{
    /** @test */
    public function a_user_can_update_their_avatar() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create(['avatar' => $original = uuid()]);

            Image::put($original, File::get(public_path('img/user.png')));

            $browser->loginAs($user)
                ->visitRoute('account')
                ->assertTitle('Account')
                ->assertSee('Account');

            $browser->switchTab('profile');

            $browser->assertSee('Photo');

            $browser->attach('avatar', public_path('img/user.png'));

            $browser->assertRouteIs('account')
                ->assertTitle('Account')
                ->assertSee('Account');

            $browser->assertSee('Your account has been updated');

            $this->assertNotEquals($original, $user->fresh()->avatar);
        });
    }
}
