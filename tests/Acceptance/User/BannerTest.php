<?php

namespace Tests\Acceptance\User;

use App\Models\User;
use App\Models\Banner;
use App\Types\Browser;
use App\Types\ClientTest;

class BannerTest extends ClientTest
{
    /** @test */
    public function a_user_can_view_the_banners_page() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $browser->loginAs($user)
                ->visitRoute('banners')
                ->assertTitle('Banners')
                ->assertSee('Banners');

            $browser->assertSee($banner->name)
                ->assertSee($banner->url);
        });
    }

    /** @test */
    public function a_user_can_store_a_banner() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->make();

            $browser->loginAs($user)
                ->visitRoute('banners')
                ->assertTitle('Banners')
                ->assertSee('Banners');

            $browser->push('create');

            $browser->assertSee('Add a new banner');

            $browser->type('name', $banner->name)
                ->type('url', $banner->url)
                ->attach('graphic', public_path('img/user.png'))
                ->push('save');

            $browser->assertRouteIs('banners')
                ->assertTitle('Banners')
                ->assertSee('Banners');

            $browser->assertSee('The banner has been created');

            $browser->assertSee($banner->name)
                ->assertSee($banner->url);
        });
    }

    /** @test */
    public function a_user_cannot_store_a_banner_if_they_have_reached_the_limit() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            Banner::factory()
                ->count(config('system.banner_limit'))
                ->belongsTo($user)
                ->create();

            $browser->loginAs($user)
                ->visitRoute('banners')
                ->assertTitle('Banners')
                ->assertSee('Banners');

            $browser->assertSee('You have reached the limit for banners and cannot add any more.');
        });
    }

    /** @test */
    public function a_user_can_update_a_banner() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $payload = Banner::factory()
                ->belongsTo($user)
                ->make();

            $browser->loginAs($user)
                ->visitRoute('banners')
                ->assertTitle('Banners')
                ->assertSee('Banners');

            $browser->assertSee($banner->name)
                ->assertSee($banner->url);

            $browser->click("edit-{$banner->id}");

            $browser->assertSee('Edit an existing banner');

            $browser->type('name', $payload->name)
                ->type('url', $payload->url)
                ->attach('graphic', public_path('img/user.png'))
                ->push('save');

            $browser->assertRouteIs('banners')
                ->assertTitle('Banners')
                ->assertSee('Banners');

            $browser->assertSee('The banner has been updated');

            $browser->assertSee($payload->name)
                ->assertSee($payload->url);
        });
    }

    /** @test */
    public function a_user_can_delete_a_banner() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $browser->loginAs($user)
                ->visitRoute('banners')
                ->assertTitle('Banners')
                ->assertSee('Banners');

            $browser->assertSee($banner->name)
                ->assertSee($banner->url);

            $browser->click("delete-{$banner->id}")
                ->confirm();

            $browser->assertRouteIs('banners')
                ->assertTitle('Banners')
                ->assertSee('Banners');

            $browser->assertSee('The banner has been deleted');

            $browser->assertDontSee($banner->name)
                ->assertDontSee($banner->url);
        });
    }
}
