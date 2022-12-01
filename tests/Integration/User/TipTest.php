<?php

namespace Tests\Integration\User;

use App\Models\Tip;
use App\Models\User;
use App\Models\Banner;
use App\Storage\Image;
use App\Enums\UserType;
use App\Types\ServerTest;
use Illuminate\Support\Str;

class TipTest extends ServerTest
{
    /** @test */
    public function a_user_can_view_the_tips_page() : void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('tips'))
            ->assertSuccessful()
            ->assertPage('tips.view.index');
    }

    /** @test */
    public function a_user_can_create_a_tip() : void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('tips.create'))
            ->assertSuccessful()
            ->assertPage('tips.create.index');
    }

    /** @test */
    public function a_user_can_edit_a_tip() : void
    {
        $user = User::factory()->create();

        $tip = Tip::factory()
            ->belongsTo($user)
            ->create();

        $this->actingAs($user)
            ->get(route('tips.edit', ['tip' => $tip]))
            ->assertSuccessful()
            ->assertPage('tips.edit.index');
    }

    /** @test */
    public function a_user_can_store_a_tip() : void
    {
        $user = User::factory()->create();

        $banner = Banner::factory()
            ->belongsTo($user)
            ->create();

        $tip = Tip::factory()
            ->belongsTo($user, $banner)
            ->make(['published_at' => now()->addDays(rand(1, 100))]);

        Image::fakeTemporary($tip->card);

        $this->actingAs($user)
            ->postJson(route('tips.store'), $tip->toArray())
            ->assertRedirect(route('tips.edit', ['tip' => Tip::first()]))
            ->assertNotification('The tip has been created');

        $this->assertCount(1, Tip::get());

        $this->assertTrue(Tip::first()->user->is($user));
        $this->assertTrue(Tip::first()->banner->is($banner));

        $this->assertEquals(Tip::first()->title, $tip->title);
        $this->assertEquals(Tip::first()->summary, $tip->summary);
        $this->assertEquals(Tip::first()->teaser, $tip->teaser);
        $this->assertEquals(Tip::first()->content, $tip->content);
        $this->assertEquals(Tip::first()->theme, $tip->theme);
        $this->assertEquals(Tip::first()->gradient, $tip->gradient);
        $this->assertEquals(Tip::first()->first_tag, $tip->first_tag);
        $this->assertEquals(Tip::first()->second_tag, $tip->second_tag);
        $this->assertEquals(Tip::first()->third_tag, $tip->third_tag);
        $this->assertEquals(Tip::first()->fourth_tag, $tip->fourth_tag);
        $this->assertEquals(Tip::first()->attribution, $tip->attribution);
        $this->assertEquals(Tip::first()->shared, $tip->shared);
        $this->assertEquals(Tip::first()->published_at, $tip->published_at);
        $this->assertEquals(Tip::first()->slug, Str::slug(implode('-', [Tip::first()->id, $tip->title])));

        Image::assertExists(Tip::first()->card);
    }

    /** @test */
    public function a_user_can_update_a_tip() : void
    {
        $user = User::factory()->create();

        $banner = Banner::factory()
            ->belongsTo($user)
            ->create();

        $tip = Tip::factory()
            ->belongsTo($user, $banner)
            ->create(['published_at' => now()->addDays(10)]);

        $payload = Tip::factory()
            ->belongsTo($user, $banner)
            ->make(['published_at' => now()->addDays(5)]);

        Image::fake($tip->card);
        Image::fakeTemporary($payload->card);

        $this->actingAs($user)
            ->patchJson(route('tips.update', ['tip' => $tip]), $payload->toArray())
            ->assertRedirect(route('tips.edit', ['tip' => $tip]))
            ->assertNotification('The tip has been updated');

        $this->assertTrue($tip->fresh()->user->is($user));
        $this->assertTrue($tip->fresh()->banner->is($banner));

        $this->assertEquals($tip->fresh()->title, $payload->title);
        $this->assertEquals($tip->fresh()->summary, $payload->summary);
        $this->assertEquals($tip->fresh()->teaser, $payload->teaser);
        $this->assertEquals($tip->fresh()->content, $payload->content);
        $this->assertEquals($tip->fresh()->theme, $payload->theme);
        $this->assertEquals($tip->fresh()->gradient, $payload->gradient);
        $this->assertEquals($tip->fresh()->first_tag, $payload->first_tag);
        $this->assertEquals($tip->fresh()->second_tag, $payload->second_tag);
        $this->assertEquals($tip->fresh()->third_tag, $payload->third_tag);
        $this->assertEquals($tip->fresh()->fourth_tag, $payload->fourth_tag);
        $this->assertEquals($tip->fresh()->attribution, $payload->attribution);
        $this->assertEquals($tip->fresh()->shared, $payload->shared);
        $this->assertEquals($tip->fresh()->published_at, $payload->published_at);

        Image::assertMissing($tip->card);
        Image::assertExists($tip->fresh()->card);
    }

    /** @test */
    public function a_user_can_delete_a_tip() : void
    {
        $user = User::factory()->create(['type' => UserType::CUSTOMER]);

        $tip = Tip::factory()
            ->belongsTo($user)
            ->create();

        Image::fake($tip->card);

        $this->actingAs($user)
            ->deleteJson(route('tips.delete', ['tip' => $tip]))
            ->assertRedirect(route('tips'))
            ->assertNotification('The tip has been deleted');

        $this->assertCount(0, Tip::get());

        Image::assertMissing($tip->card);
    }

    /** @test */
    public function a_user_cannot_delete_a_tip_that_does_not_belong_to_them() : void
    {
        $user_1 = User::factory()->create(['type' => UserType::CUSTOMER]);
        $user_2 = User::factory()->create(['type' => UserType::CUSTOMER]);

        $tip = Tip::factory()
            ->belongsTo($user_2)
            ->create();

        $this->actingAs($user_1)
            ->deleteJson(route('tips.delete', ['tip' => $tip]))
            ->assertForbidden();
    }

    /** @test */
    public function a_user_can_delete_a_tip_that_does_not_belong_to_them_when_they_are_an_employee() : void
    {
        $user_1 = User::factory()->create(['type' => UserType::EMPLOYEE]);
        $user_2 = User::factory()->create(['type' => UserType::CUSTOMER]);

        $tip = Tip::factory()
            ->belongsTo($user_2)
            ->create();

        Image::fake($tip->card);

        $this->actingAs($user_1)
            ->deleteJson(route('tips.delete', ['tip' => $tip]))
            ->assertRedirect(route('tips'))
            ->assertNotification('The tip has been deleted');

        $this->assertCount(0, Tip::get());

        Image::assertMissing($tip->card);
    }
}
