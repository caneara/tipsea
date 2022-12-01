<?php

namespace Tests\Unit\Rules;

use App\Types\ServerTest;
use App\Rules\ForbiddenContentRule;

class ForbiddenContentRuleTest extends ServerTest
{
    /** @test */
    public function it_confirms_the_validation_rule_works_as_expected() : void
    {
        $rule_1 = ['email' => [ForbiddenContentRule::make('domains', '')]];
        $rule_2 = ['email' => [ForbiddenContentRule::make('domains', 'test@example.com')]];
        $rule_3 = ['email' => [ForbiddenContentRule::make('domains', 'test@caneara.com')]];
        $rule_4 = ['email' => [ForbiddenContentRule::make('domains', 'test@lumeno.dev')]];
        $rule_5 = ['email' => [ForbiddenContentRule::make('domains', 'test@tipsea.app')]];

        $tests = [
            ['email' => 'test@example.com',        'fails' => [false, false, false, false, false]],
            ['email' => 'test@gmail.com',          'fails' => [false, false, false, false, false]],
            ['email' => 'test@blah-gmail.com',     'fails' => [false, false, false, false, false]],
            ['email' => 'john.doe@blah-gmail.com', 'fails' => [false, false, false, false, false]],
            ['email' => 'john_doe@blah_gmail.com', 'fails' => [false, false, false, false, false]],
            ['email' => 'test@caneara.com',        'fails' => [true,  true,  false, false, false]],
            ['email' => 'test@Caneara.com',        'fails' => [true,  true,  false, false, false]],
            ['email' => 'test.test@caneara.com',   'fails' => [true,  true,  false, false, false]],
            ['email' => 'test_test@Caneara.com',   'fails' => [true,  true,  false, false, false]],
            ['email' => 'test@lumeno.dev',         'fails' => [true,  true,  false, false, false]],
            ['email' => 'test@Lumeno.dev',         'fails' => [true,  true,  false, false, false]],
            ['email' => 'test.test@lumeno.dev',    'fails' => [true,  true,  false, false, false]],
            ['email' => 'test_test@Lumeno.dev',    'fails' => [true,  true,  false, false, false]],
            ['email' => 'test@tipsea.app',         'fails' => [true,  true,  false, false, false]],
            ['email' => 'test@TipSea.app',         'fails' => [true,  true,  false, false, false]],
            ['email' => 'test.test@tipsea.app',    'fails' => [true,  true,  false, false, false]],
            ['email' => 'test_test@TipSea.app',    'fails' => [true,  true,  false, false, false]],
        ];

        foreach ([$rule_1, $rule_2, $rule_3, $rule_4, $rule_5] as $key => $rule) {
            foreach ($tests as $test) {
                $this->assertEquals(
                    $test['fails'][$key],
                    validator(['email' => $test['email']], $rule)->fails()
                );
            }
        }
    }
}
