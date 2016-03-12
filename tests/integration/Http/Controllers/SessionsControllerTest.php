<?php

namespace Test\Http\Controllers;

class SessionsControllerTest extends AuthHelper
{
    /** @test */
    public function it_logs_a_user_in()
    {
        $this->createTestStub(['activated' => 1]);

        $this->login()
            ->see($this->user->name . '님, 환영합니다.');
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
            ->see('이메일 또는 비밀번호가 맞지 않습니다.');
    }

    /** @test */
    public function it_fails_login_when_user_is_not_confirmed()
    {
        $this->createTestStub(['activated' => 0]);

        $this->login()
            ->seePageIs(route('sessions.create'))
            ->see('가입확인해 주세요.');
    }

    /** @test */
    public function it_fails_login_when_credentials_not_match()
    {
        $this->createTestStub();

        $this->login(['password' => 'wrong.password'])
            ->see('이메일 또는 비밀번호가 맞지 않습니다.')
            ->seePageIs(route('sessions.create'));
    }

    /** @test */
    public function it_logs_a_user_out()
    {
        $this->createTestStub();

        $this->actingAs($this->user)
            ->logout()
            ->seePageIs('/')
            ->see('또 방문해 주세요.');
    }
}