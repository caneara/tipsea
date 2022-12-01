<?php

namespace Tests\Integration\User;

use App\Models\Tip;
use App\Models\User;
use App\Models\Banner;
use App\Storage\Image;
use App\Types\ServerTest;

class BannerTest extends ServerTest
{
    /** @test */
    public function a_user_can_view_the_banners_page() : void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('banners'))
            ->assertSuccessful()
            ->assertPage('banners.index');
    }

    /** @test */
    public function a_user_can_store_a_banner() : void
    {
        $user = User::factory()->create();

        $banner = Banner::factory()
            ->belongsTo($user)
            ->make();

        Image::fakeTemporary($banner->graphic);

        $this->actingAs($user)
            ->postJson(route('banners.store'), $banner->toArray())
            ->assertRedirect(route('banners'))
            ->assertNotification('The banner has been created');

        $this->assertCount(1, Banner::get());

        $this->assertTrue(Banner::first()->user->is($user));

        $this->assertEquals(Banner::first()->url, $banner->url);
        $this->assertEquals(Banner::first()->name, $banner->name);

        Image::assertExists(Banner::first()->graphic);
    }

    /** @test */
    public function a_user_cannot_store_a_banner_if_they_have_reached_the_limit() : void
    {
        $user = User::factory()->create();

        config(['system.banner_limit' => 1]);

        Banner::factory()
            ->belongsTo($user)
            ->create();

        $banner = Banner::factory()
            ->belongsTo($user)
            ->make();

        $this->actingAs($user)
            ->postJson(route('banners.store'), $banner->toArray())
            ->assertForbidden();
    }

    /** @test */
    public function a_user_can_update_a_banner() : void
    {
        $user = User::factory()->create();

        $banner = Banner::factory()
            ->belongsTo($user)
            ->create();

        $payload = Banner::factory()
            ->belongsTo($user)
            ->make();

        Image::fake($banner->graphic);
        Image::fakeTemporary($payload->graphic);

        $this->actingAs($user)
            ->patchJson(route('banners.update', ['banner' => $banner]), $payload->toArray())
            ->assertRedirect(route('banners'))
            ->assertNotification('The banner has been updated');

        $this->assertEquals($banner->fresh()->url, $payload->url);
        $this->assertEquals($banner->fresh()->name, $payload->name);

        Image::assertMissing($banner->graphic);
        Image::assertExists($banner->fresh()->graphic);
    }

    /** @test */
    public function a_user_can_delete_a_banner() : void
    {
        $user = User::factory()->create();

        $banner = Banner::factory()
            ->belongsTo($user)
            ->create();

        $tip = Tip::factory()
            ->belongsTo($user, $banner)
            ->create();

        Image::fake($banner->graphic);

        $this->actingAs($user)
            ->deleteJson(route('banners.delete', ['banner' => $banner]))
            ->assertRedirect(route('banners'))
            ->assertNotification('The banner has been deleted');

        $this->assertCount(0, Banner::get());

        $this->assertNull($tip->fresh()->banner_id);

        Image::assertMissing($banner->graphic);
    }
}
