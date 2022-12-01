<?php

namespace Tests\Unit\Rules;

use App\Storage\Image;
use App\Rules\ImageRule;
use App\Types\ServerTest;

class ImageRuleTest extends ServerTest
{
    /** @test */
    public function it_confirms_the_validation_rule_works_as_expected() : void
    {
        $rule = ['file' => [ImageRule::make()]];

        Image::fake('test_1');
        Image::fakeTemporary('test_2', 'gif');
        Image::fakeTemporary('test_3', 'jpeg');
        Image::fakeTemporary('test_4', 'png');

        $this->assertTrue(validator(['file' => 'test_0'], $rule)->fails());
        $this->assertTrue(validator(['file' => 'test_1'], $rule)->fails());
        $this->assertTrue(validator(['file' => 'test_2'], $rule)->fails());
        $this->assertFalse(validator(['file' => 'test_3'], $rule)->fails());
        $this->assertFalse(validator(['file' => 'test_4'], $rule)->fails());
    }
}
