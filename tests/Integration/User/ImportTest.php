<?php

namespace Tests\Integration\User;

use App\Models\Tip;
use App\Models\User;
use App\Models\Banner;
use App\Storage\Image;
use App\Types\ServerTest;
use Illuminate\Support\Str;
use Illuminate\Http\Response;

class ImportTest extends ServerTest
{
    /** @test */
    public function a_user_can_view_the_import_page() : void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('imports'))
            ->assertSuccessful()
            ->assertPage('import.index');
    }

    /** @test */
    public function a_user_can_verify_an_import_with_one_tip() : void
    {
        $user = User::factory()->create();

        $banner = Banner::factory()
            ->belongsTo($user)
            ->create();

        $tip = Tip::factory()
            ->belongsTo($user, $banner)
            ->make(['published_at' => now()->subDays(rand(1, 100))]);

        $payload = json_encode([
            array_merge($tip->toArray(), ['shared' => true]),
        ]);

        $this->actingAs($user)
            ->postJson(route('imports.verify'), compact('payload'))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertExactJson(['valid']);
    }

    /** @test */
    public function a_user_can_verify_an_import_with_multiple_tips() : void
    {
        $user = User::factory()->create();

        $banner = Banner::factory()
            ->belongsTo($user)
            ->create();

        $tip_1 = Tip::factory()
            ->belongsTo($user, $banner)
            ->make(['published_at' => now()->subDays(rand(1, 100))]);

        $tip_2 = Tip::factory()
            ->belongsTo($user, $banner)
            ->make(['published_at' => now()->subDays(rand(1, 100))]);

        $payload = json_encode([
            array_merge($tip_1->toArray(), ['shared' => true]),
            array_merge($tip_2->toArray(), ['shared' => true]),
        ]);

        $this->actingAs($user)
            ->postJson(route('imports.verify'), compact('payload'))
            ->assertStatus(Response::HTTP_ACCEPTED)
            ->assertExactJson(['valid']);
    }

    /** @test */
    public function a_user_can_store_an_import_with_one_tip() : void
    {
        $user = User::factory()->create();

        $banner = Banner::factory()
            ->belongsTo($user)
            ->create();

        $tip = Tip::factory()
            ->belongsTo($user, $banner)
            ->make(['published_at' => now()->subDays(rand(1, 100))]);

        Image::fakeTemporary($tip->card);

        $payload = json_encode([
            array_merge($tip->toArray(), ['shared' => true]),
        ]);

        $this->actingAs($user)
            ->postJson(route('imports.store'), compact('payload'))
            ->assertRedirect(route('tips'))
            ->assertNotification('The tips have been imported');

        $this->assertCount(1, Tip::get());

        $this->assertTrue(Tip::first()->user->is($user));
        $this->assertTrue(Tip::first()->banner->is($banner));

        $this->assertTrue(Tip::first()->shared);

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
        $this->assertEquals(Tip::first()->published_at, $tip->published_at);
        $this->assertEquals(Tip::first()->slug, Str::slug(implode('-', [Tip::first()->id, $tip->title])));

        Image::assertExists(Tip::first()->card);
    }

    /** @test */
    public function a_user_can_store_an_import_with_multiple_tips() : void
    {
        $user = User::factory()->create();

        $banner = Banner::factory()
            ->belongsTo($user)
            ->create();

        $tip_1 = Tip::factory()
            ->belongsTo($user, $banner)
            ->make(['published_at' => now()->subDays(rand(1, 100))]);

        $tip_2 = Tip::factory()
            ->belongsTo($user, $banner)
            ->make(['published_at' => now()->subDays(rand(1, 100))]);

        Image::fakeTemporary($tip_1->card);
        Image::fakeTemporary($tip_2->card);

        $payload = json_encode([
            array_merge($tip_1->toArray(), ['shared' => true]),
            array_merge($tip_2->toArray(), ['shared' => true]),
        ]);

        $this->actingAs($user)
            ->postJson(route('imports.store'), compact('payload'))
            ->assertRedirect(route('tips'))
            ->assertNotification('The tips have been imported');

        $this->assertCount(2, Tip::get());

        $this->assertTrue(Tip::first()->user->is($user));
        $this->assertTrue(Tip::second()->user->is($user));
        $this->assertTrue(Tip::first()->banner->is($banner));
        $this->assertTrue(Tip::second()->banner->is($banner));

        $this->assertTrue(Tip::first()->shared);

        $this->assertEquals(Tip::first()->title, $tip_1->title);
        $this->assertEquals(Tip::first()->summary, $tip_1->summary);
        $this->assertEquals(Tip::first()->teaser, $tip_1->teaser);
        $this->assertEquals(Tip::first()->content, $tip_1->content);
        $this->assertEquals(Tip::first()->theme, $tip_1->theme);
        $this->assertEquals(Tip::first()->gradient, $tip_1->gradient);
        $this->assertEquals(Tip::first()->first_tag, $tip_1->first_tag);
        $this->assertEquals(Tip::first()->second_tag, $tip_1->second_tag);
        $this->assertEquals(Tip::first()->third_tag, $tip_1->third_tag);
        $this->assertEquals(Tip::first()->fourth_tag, $tip_1->fourth_tag);
        $this->assertEquals(Tip::first()->attribution, $tip_1->attribution);
        $this->assertEquals(Tip::first()->published_at, $tip_1->published_at);
        $this->assertEquals(Tip::first()->slug, Str::slug(implode('-', [Tip::first()->id, $tip_1->title])));

        $this->assertTrue(Tip::second()->shared);

        $this->assertEquals(Tip::second()->title, $tip_2->title);
        $this->assertEquals(Tip::second()->summary, $tip_2->summary);
        $this->assertEquals(Tip::second()->teaser, $tip_2->teaser);
        $this->assertEquals(Tip::second()->content, $tip_2->content);
        $this->assertEquals(Tip::second()->theme, $tip_2->theme);
        $this->assertEquals(Tip::second()->gradient, $tip_2->gradient);
        $this->assertEquals(Tip::second()->first_tag, $tip_2->first_tag);
        $this->assertEquals(Tip::second()->second_tag, $tip_2->second_tag);
        $this->assertEquals(Tip::second()->third_tag, $tip_2->third_tag);
        $this->assertEquals(Tip::second()->fourth_tag, $tip_2->fourth_tag);
        $this->assertEquals(Tip::second()->attribution, $tip_2->attribution);
        $this->assertEquals(Tip::second()->published_at, $tip_2->published_at);
        $this->assertEquals(Tip::second()->slug, Str::slug(implode('-', [Tip::second()->id, $tip_2->title])));

        Image::assertExists(Tip::first()->card);
        Image::assertExists(Tip::second()->card);
    }
}
