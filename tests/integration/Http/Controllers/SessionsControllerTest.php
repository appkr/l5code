<?php

namespace Test\Http\Controllers;

class SessionsControllerTest extends AuthHelper
{
    /** @test */
    public function 로그인한다()
    {
        $this->createTestStub(['activated' => 1]);

        $this->login()
             ->see(
                 trans('auth.sessions.info_welcome', ['name' => $this->user->name])
             );
    }

    /** @test */
    public function 사용자_입력값이_유효하지_않으면_오류난다()
    {
        $this->createTestStub();

        $this->login(['email' => 'malformed.email', 'password' => 'short'])
             ->see(trans('validation.email', ['attribute' => 'email']))
             ->see(trans('validation.min.string', ['attribute' => 'password', 'min' => 6]))
             ->seeRouteIs('sessions.create');
    }

    /** @test */
    public function 회원_정보가_정확하지않으면_로그인할_수_없다()
    {
        $this->createTestStub(['activated' => 1]);

        $this->login(['password' => 'wrong_password'])
             ->seeRouteIs('sessions.create')
             ->see(
                 trans('auth.sessions.error_incorrect_credentials')
             );
    }

    /** @test */
    public function 회원가입_확인하지_않은_사용자는_로그인할_수_없다()
    {
        $this->createTestStub(['activated' => 0]);

        $this->login()
             ->seeRouteIs('sessions.create')
             ->see(
                 trans('auth.sessions.error_not_confirmed')
             );
    }

    /** @test */
    public function 비밀번호가_틀리면_로그인할_수_없다()
    {
        $this->createTestStub();

        $this->login(['password' => 'wrong.password'])
             ->see(
                 trans('auth.sessions.error_incorrect_credentials')
             )
             ->seeRouteIs('sessions.create');
    }

    /** @test */
    public function 로그아웃한다()
    {
        $this->createTestStub();

        $this->actingAs($this->user)
             ->logout()
             ->seeRouteIs('root')
             ->see(
                 trans('auth.sessions.info_bye')
             );
    }
}