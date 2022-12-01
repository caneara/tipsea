<?php

namespace Tests\Unit\Commands;

use App\Models\Tip;
use App\Models\User;
use App\Storage\Image;
use App\Types\ServerTest;
use Illuminate\Support\Str;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class ProcessTipsCommandTest extends ServerTest
{
    /** @test */
    public function it_processes_tips_that_are_scheduled_for_publication() : void
    {
        Http::fake([
            'twitter.com/1.1/media/upload.json'          => Http::response(['media_id' => '3']),
            'twitter.com/1.1/media/metadata/create.json' => Http::response('done'),
            'twitter.com/1.1/statuses/update.json'       => Http::response(['id' => '4']),
        ]);

        $user_1 = User::factory()->create();
        $user_2 = User::factory()->create(['integration' => ['token' => '1', 'secret' => '2']]);
        $user_3 = User::factory()->create(['integration' => ['token' => '1', 'secret' => '2']]);
        $user_4 = User::factory()->create(['integration' => ['token' => '1', 'secret' => '2']]);
        $user_5 = User::factory()->create(['integration' => ['token' => '1', 'secret' => '2']]);
        $user_6 = User::factory()->create(['integration' => ['token' => '1', 'secret' => '2']]);

        $tip_1 = Tip::factory()->belongsTo($user_1)->create([
            'shared'       => false,
            'published_at' => now()->subHour(),
        ]);

        $tip_2 = Tip::factory()->belongsTo($user_2)->create([
            'shared'       => true,
            'published_at' => now()->subHour(),
        ]);

        $tip_3 = Tip::factory()->belongsTo($user_3)->create([
            'shared'       => false,
            'published_at' => now()->subDays(3),
        ]);

        $tip_4 = Tip::factory()->belongsTo($user_4)->create([
            'shared'       => false,
            'published_at' => now()->addHour(),
        ]);

        $tip_5 = Tip::factory()->belongsTo($user_5)->create([
            'title'        => Str::random(100),
            'summary'      => 'summary_5',
            'teaser'       => 'test_5',
            'first_tag'    => 'PHP',
            'shared'       => false,
            'published_at' => now(),
        ]);

        $tip_6 = Tip::factory()->belongsTo($user_6)->create([
            'title'        => Str::random(100),
            'summary'      => 'summary_6',
            'teaser'       => 'test_6',
            'first_tag'    => 'Python',
            'shared'       => false,
            'published_at' => now()->subHour(),
        ]);

        Image::fake($tip_5->card);
        Image::fake($tip_6->card);

        Image::put($tip_5->card, Storage::get("images/{$tip_5->card}.jpeg"));
        Image::put($tip_6->card, Storage::get("images/{$tip_6->card}.jpeg"));

        $tip_1->update(['slug' => Str::slug("{$tip_1->id}-{$tip_1->title}")]);
        $tip_2->update(['slug' => Str::slug("{$tip_2->id}-{$tip_2->title}")]);
        $tip_3->update(['slug' => Str::slug("{$tip_3->id}-{$tip_3->title}")]);
        $tip_4->update(['slug' => Str::slug("{$tip_4->id}-{$tip_4->title}")]);
        $tip_5->update(['slug' => Str::slug("{$tip_5->id}-{$tip_5->title}")]);
        $tip_6->update(['slug' => Str::slug("{$tip_6->id}-{$tip_6->title}")]);

        $this->artisan('system:process-tips')
            ->expectsOutput('The tips are being processed');

        Http::assertSentCount(8);

        Http::assertSent(function(Request $request) use ($tip_5) {
            return $request->method('POST') &&
                $request->header('Accept', 'application/json') &&
                $request->header('User-Agent', config('app.agent')) &&
                $request->url() == 'https://upload.twitter.com/1.1/media/upload.json' &&
                $request->data()[0]['name'] === 'media_category' &&
                $request->data()[0]['contents'] === 'tweet_image' &&
                $request->data()[1]['name'] === 'media' &&
                $request->data()[1]['contents'] === Image::get($tip_5->card);
        });

        Http::assertSent(function(Request $request) {
            return $request->method('POST') &&
                $request->header('Accept', 'application/json') &&
                $request->header('User-Agent', config('app.agent')) &&
                $request->url() == 'https://upload.twitter.com/1.1/media/metadata/create.json' &&
                $request->data()['media_id'] === '3' &&
                $request->data()['alt_text']['text'] === 'test_5';
        });

        Http::assertSent(function(Request $request) {
            return $request->method('POST') &&
                $request->header('Accept', 'application/json') &&
                $request->header('User-Agent', config('app.agent')) &&
                $request->url() == 'https://api.twitter.com/1.1/statuses/update.json' &&
                $request['status'] === "#PHP Tip:\n\nsummary_5...\n\n👇 example in link below" &&
                $request['media_ids'] === '3' &&
                $request['trim_user'] === true;
        });

        Http::assertSent(function(Request $request) use ($tip_5) {
            return $request->method('POST') &&
                $request->header('Accept', 'application/json') &&
                $request->header('User-Agent', config('app.agent')) &&
                $request->url() == 'https://api.twitter.com/1.1/statuses/update.json' &&
                $request['status'] === '🔗 ' . route('tips.show', ['tip' => $tip_5, 'no_embed' => true]) &&
                $request['in_reply_to_status_id'] === '4' &&
                $request['trim_user'] === true;
        });

        Http::assertSent(function(Request $request) use ($tip_6) {
            return $request->method('POST') &&
                $request->header('Accept', 'application/json') &&
                $request->header('User-Agent', config('app.agent')) &&
                $request->url() == 'https://upload.twitter.com/1.1/media/upload.json' &&
                $request->data()[0]['name'] === 'media_category' &&
                $request->data()[0]['contents'] === 'tweet_image' &&
                $request->data()[1]['name'] === 'media' &&
                $request->data()[1]['contents'] === Image::get($tip_6->card);
        });

        Http::assertSent(function(Request $request) {
            return $request->method('POST') &&
                $request->header('Accept', 'application/json') &&
                $request->header('User-Agent', config('app.agent')) &&
                $request->url() == 'https://upload.twitter.com/1.1/media/metadata/create.json' &&
                $request->data()['media_id'] === '3' &&
                $request->data()['alt_text']['text'] === 'test_6';
        });

        Http::assertSent(function(Request $request) {
            return $request->method('POST') &&
                $request->header('Accept', 'application/json') &&
                $request->header('User-Agent', config('app.agent')) &&
                $request->url() == 'https://api.twitter.com/1.1/statuses/update.json' &&
                $request['status'] === "#Python Tip:\n\nsummary_6...\n\n👇 example in link below" &&
                $request['media_ids'] === '3' &&
                $request['trim_user'] === true;
        });

        Http::assertSent(function(Request $request) use ($tip_6) {
            return $request->method('POST') &&
                $request->header('Accept', 'application/json') &&
                $request->header('User-Agent', config('app.agent')) &&
                $request->url() == 'https://api.twitter.com/1.1/statuses/update.json' &&
                $request['status'] === '🔗 ' . route('tips.show', ['tip' => $tip_6, 'no_embed' => true]) &&
                $request['in_reply_to_status_id'] === '4' &&
                $request['trim_user'] === true;
        });
    }
}
