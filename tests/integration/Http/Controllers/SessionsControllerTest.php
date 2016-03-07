<?php

namespace Test\Http\Controllers;

class SessionsControllerTest extends AuthHelper
{
    /** @test */
    public function it_logs_a_user_in()
    {
        $this->createTestStub(['activated' => 1]);

        $this->login()
            ->see(trans(
                'auth.sessions.info_welcome',
                ['name' => $this->user->name]
            ));
    }

    /** @test */
    public function it_fails_login_when_validation_fails()
    {
        $this->createTestStub();

        $this->login(['email' => 'malformed.email', 'password' => 'short'])
            ->see(trans('validation.email', ['attribute' => 'email']))
            ->see(trans('validation.min.string', ['attribute' => 'password', 'min' => 6]))
            ->seePageIs(route('sessions.create'));
    }

    /** @test */
    public function it_fails_login_when_credentials_are_not_correct()
    {
        $this->createTestStub(['activated' => 1]);

        $this->login(['password' => 'wrong_password'])
            ->seePageIs(route('sessions.create'))
            ->see(trans('auth.sessions.error_incorrect_credentials'));
    }

    /** @test */
    public function it_fails_login_when_user_is_not_confirmed()
    {
        $this->createTestStub(['activated' => 0]);

        $this->login()
            ->seePageIs(route('sessions.create'))
            ->see(trans('auth.sessions.error_not_confirmed'));
    }

    /** @test */
    public function it_logs_a_user_out()
    {
        $this->createTestStub();

        $this->actingAs($this->user)
            ->logout()
            ->seePageIs('/')
            ->see(trans('auth.sessions.info_bye'));
    }
}