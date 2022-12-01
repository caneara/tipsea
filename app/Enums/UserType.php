<?php

namespace App\Enums;

enum UserType : int
{
    /**
     * The available options.
     *
     */
    case CUSTOMER = 1;
    case EMPLOYEE = 2;

    /**
     * Determine if the current instance is a customer.
     *
     */
    public function isCustomer() : bool
    {
        return $this === static::CUSTOMER;
    }

    /**
     * Determine if the current instance is a employee.
     *
     */
    public function isEmployee() : bool
    {
        return $this === static::EMPLOYEE;
    }
}
