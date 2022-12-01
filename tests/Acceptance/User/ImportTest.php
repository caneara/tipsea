<?php

namespace Tests\Acceptance\User;

use App\Models\Tip;
use App\Models\User;
use App\Models\Banner;
use App\Types\Browser;
use App\Types\ClientTest;
use Illuminate\Support\Str;

class ImportTest extends ClientTest
{
    /** @test */
    public function a_user_can_verify_a_schema() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $json_1 = '[{
                "title": "Indexing database queries",
                "summary": "Excepturi impedit totam culpa aut excepturi omnis molestiae. Dolor nihil et et aperiam eligendi in.",
                "teaser": "Atque eveniet provident numquam ad quae quia rem. Aut sint dolorem error nostrum voluptatem aut sint ullam.",
                "first_tag": "Python",';

            $json_2 = '[{
                "summary": "Excepturi impedit totam culpa aut excepturi omnis molestiae. Dolor nihil et et aperiam eligendi in.",
                "teaser": "Atque eveniet provident numquam ad quae quia rem. Aut sint dolorem error nostrum voluptatem aut sint ullam.",
                "first_tag": "Python",
                "second_tag": "JavaScript",
                "third_tag": "C#",
                "fourth_tag": "",
                "content": "Voluptatibus totam eaque dolorem harum in doloribus. Autem sunt id exercitationem a. Iusto iusto explicabo ea non aut.",
                "published_at": "2021-06-13T08:30:00.000Z"
            }]';

            $browser->loginAs($user)
                ->visitRoute('imports')
                ->assertTitle('Import')
                ->assertSee('Import Tips');

            $browser->type('json', $json_1)
                ->push('save', 2000);

            $browser->assertRouteIs('imports')
                ->assertTitle('Import')
                ->assertSee('Import Tips');

            $browser->assertSee(Str::upper('Invalid JSON'))
                ->assertDontSee(Str::upper('The JSON file does not conform to the required schema.'));

            $browser->type('json', $json_2)
                ->push('save', 2000);

            $browser->assertRouteIs('imports')
                ->assertTitle('Import')
                ->assertSee('Import Tips');

            $browser->assertDontSee(Str::upper('Invalid JSON'))
                ->assertSee(Str::upper('The JSON file does not conform to the required schema.'));
        });
    }

    /** @test */
    public function a_user_can_import_a_tip() : void
    {
        $this->browse(function(Browser $browser) {
            $user = User::factory()->create();

            $banner = Banner::factory()
                ->belongsTo($user)
                ->create();

            $json = '[{
                "title": "Indexing database queries",
                "summary": "Excepturi impedit totam culpa aut excepturi omnis molestiae. Dolor nihil et et aperiam eligendi in.",
                "teaser": "Atque eveniet provident numquam ad quae quia rem. Aut sint dolorem error nostrum voluptatem aut sint ullam.",
                "first_tag": "Python",
                "second_tag": "JavaScript",
                "third_tag": "C#",
                "fourth_tag": "",
                "content": "Voluptatibus totam eaque dolorem harum in doloribus. Autem sunt id exercitationem a. Iusto iusto explicabo ea non aut.",
                "published_at": "2021-06-13T08:30:00.000Z"
            }]';

            $tip = json_decode($json)[0];

            $browser->loginAs($user)
                ->visitRoute('imports')
                ->assertTitle('Import')
                ->assertSee('Import Tips');

            $browser->select('banner_id', $banner->id)
                ->type('json', $json)
                ->push('save', 2000);

            $browser->assertRouteIs('tips')
                ->assertTitle('Tips')
                ->assertSee('Tips');

            $browser->assertSee('The tips have been imported');

            $browser->assertSee($tip->title)
                ->assertSee($user->name)
                ->assertSee($tip->summary)
                ->assertSee(Str::upper($tip->first_tag))
                ->assertSee(Str::upper($tip->second_tag))
                ->assertSee(Str::upper($tip->third_tag));

            $browser->assertSeeIn('@metrics_likes_' . Tip::first()->id, '0')
                ->assertSeeIn('@metrics_comments_' . Tip::first()->id, '0');
        });
    }
}
