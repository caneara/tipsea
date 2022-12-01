<?php

namespace Tests\Unit\Enums;

use App\Types\ServerTest;
use App\Enums\NotificationType;

class NotificationTypeTest extends ServerTest
{
    /** @test */
    public function it_knows_its_name() : void
    {
        $type_1 = NotificationType::LIKE;
        $type_2 = NotificationType::COMMENT;

        $this->assertEquals('like', $type_1->name());
        $this->assertEquals('comment', $type_2->name());
    }
}
