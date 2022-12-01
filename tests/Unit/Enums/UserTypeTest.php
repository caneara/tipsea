<?php

namespace Tests\Unit\Enums;

use App\Enums\UserType;
use App\Types\ServerTest;

class UserTypeTest extends ServerTest
{
    /** @test */
    public function it_knows_if_it_is_a_customer() : void
    {
        $type_1 = UserType::CUSTOMER;
        $type_2 = UserType::EMPLOYEE;

        $this->assertTrue($type_1->isCustomer());
        $this->assertFalse($type_2->isCustomer());
    }

    /** @test */
    public function it_knows_if_it_is_an_employee() : void
    {
        $type_1 = UserType::CUSTOMER;
        $type_2 = UserType::EMPLOYEE;

        $this->assertFalse($type_1->isEmployee());
        $this->assertTrue($type_2->isEmployee());
    }
}
