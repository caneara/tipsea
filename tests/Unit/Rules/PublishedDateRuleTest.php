<?php

namespace Tests\Unit\Rules;

use App\Types\ServerTest;
use App\Rules\PublishedDateRule;

class PublishedDateRuleTest extends ServerTest
{
    /** @test */
    public function it_confirms_the_validation_rule_works_as_expected() : void
    {
        $rule_1 = ['date' => [new PublishedDateRule((object) ['published_at' => null])]];
        $rule_2 = ['date' => [new PublishedDateRule((object) ['published_at' => now()->addDay()])]];
        $rule_3 = ['date' => [new PublishedDateRule((object) ['published_at' => now()->subDay()])]];

        $this->assertFalse(validator(['date' => null], $rule_1)->fails());
        $this->assertFalse(validator(['date' => now()->addDay()], $rule_1)->fails());
        $this->assertTrue(validator(['date' => now()->subDay()], $rule_1)->fails());

        $this->assertTrue(validator(['date' => null], $rule_2)->fails());
        $this->assertFalse(validator(['date' => now()->addHour()], $rule_2)->fails());
        $this->assertTrue(validator(['date' => now()->subDay()], $rule_2)->fails());

        $this->assertFalse(validator(['date' => null], $rule_3)->fails());
        $this->assertFalse(validator(['date' => ''], $rule_3)->fails());
        $this->assertTrue(validator(['date' => now()->addHour()], $rule_3)->fails());
        $this->assertTrue(validator(['date' => now()->subDay()], $rule_3)->fails());
    }
}
