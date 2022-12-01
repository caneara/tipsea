<?php

namespace Tests\Unit\Rules;

use App\Types\ServerTest;
use App\Rules\UniqueTagRule;

class UniqueTagRuleTest extends ServerTest
{
    /** @test */
    public function it_confirms_the_validation_rule_works_as_expected() : void
    {
        $rule = ['first_tag' => [new UniqueTagRule()]];

        request()->replace([
            'first_tag'  => 'PHP',
            'second_tag' => 'PHP',
            'third_tag'  => 'Python',
            'fourth_tag' => 'Vue',
        ]);

        $this->assertTrue(validator(['first_tag' => 'PHP'], $rule)->fails());

        request()->replace([
            'first_tag'  => 'PHP',
            'second_tag' => 'C#',
            'third_tag'  => 'Python',
            'fourth_tag' => 'Vue',
        ]);

        $this->assertFalse(validator(['first_tag' => 'PHP'], $rule)->fails());
    }
}
