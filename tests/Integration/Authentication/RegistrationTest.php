<?php

namespace Tests\Integration\Authentication;

use App\Models\User;
use App\Types\ServerTest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use App\Notifications\VerifyEmailAddressNotification;

class RegistrationTest extends ServerTest
{
    /** @test */
    public function a_user_can_view_the_registration_page() : void
    {
        $this->get(route('register'))
            ->assertSuccessful()
            ->assertPage('register.index');
    }

    /** @test */
    public function a_user_can_register_for_an_account() : void
    {
        Notification::fake();

        $payload = [
            'name'                  => 'John Doe',
            'handle'                => 'johndoe',
            'email'                 => 'john@example.com',
            'password'              => 'Q5p@4xFvw9w#',
            'password_confirmation' => 'Q5p@4xFvw9w#',
            'terms'                 => true,
        ];

        $this->postJson(route('register.store'), $payload)
            ->assertRedirect(route('email.verify.notice'));

        $this->assertAuthenticated();

        $this->assertCount(1, User::get());

        $this->assertTrue(User::first()->type->isCustomer());
        $this->assertNull(User::first()->email_verified_at);
        $this->assertEquals('John Doe', User::first()->name);
        $this->assertEquals('johndoe', User::first()->handle);
        $this->assertEquals('john@example.com', User::first()->email);
        $this->assertTrue(Hash::check('Q5p@4xFvw9w#', User::first()->password));

        $parameters = [
            'id'   => User::first()->id,
            'hash' => sha1($payload['email']),
        ];

        Notification::assertSent(User::first(), VerifyEmailAddressNotification::class)
            ->assertSubject('Verify Email')
            ->assertMarkdown('email.verify')
            ->assertAction('Verify Email Address', route('email.verify.confirm', $parameters), false);
    }
}
