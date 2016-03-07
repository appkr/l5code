<?php

namespace Test\Http\Controllers;

class PasswordsControllerTest extends AuthHelper
{
    /** @test */
    public function it_creates_password_reminder()
    {
        $this->expectsEvents(\App\Events\PasswordRemindCreated::class);

        $this->createTestStub();

        $this->remind()
            ->seeInDatabase('password_resets', ['email' => $this->user->email])
            ->seePageIs('/')
            ->see(trans('auth.passwords.sent_reminder'));
    }

    /** @test */
    public function it_complains_when_provided_email_NOT_exists()
    {
        $this->createTestStub();

        $this->remind(['email' => 'not_existing_email@example.com'])
            ->seePageIs(route('remind.create'))
            ->see(trans('validation.exists', ['attribute' => 'email']));
    }

    /** @test */
    public function it_resets_password()
    {
        $this->createTestStub();

        $this->reset()
            ->notSeeInDatabase('password_resets', ['email' => $this->user->email])
            ->seePageIs('/')
            ->see(trans('auth.passwords.success_reset'));
    }

    /** @test */
    public function it_stops_when_provided_email_NOT_exists()
    {
        $this->createTestStub();

        $this->reset(['email' => 'not_existing_email@example.com'])
            ->see(trans('validation.exists', ['attribute' => 'email']));
    }

    /** @test */
    public function it_stops_when_provided_token_NOT_valid()
    {
        $this->createTestStub();

        $this->reset(['token' => str_random(64)])
            ->see(trans('auth.passwords.error_wrong_url'));
    }
}