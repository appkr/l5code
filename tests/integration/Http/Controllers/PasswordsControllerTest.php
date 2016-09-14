<?php

namespace Test\Http\Controllers;

class PasswordsControllerTest extends AuthHelper
{
    /** @test */
    public function 비밀번호_바꾸기_요청을_처리한다()
    {
        $this->expectsEvents(\App\Events\PasswordRemindCreated::class);

        $this->createTestStub();

        $this->remind()
             ->seeInDatabase('password_resets', ['email' => $this->user->email])
             ->seeRouteIs('root')
             ->see('비밀번호 바꾸는 방법을 담은 이메일을 발송했습니다. 메일 박스를 확인해 주세요.');
    }

    /** @test */
    public function 잘못된_이메일로_비밀번호_바꾸기_요청하면_오류난다()
    {
        $this->createTestStub();

        $this->remind(['email' => 'not_existing_email@example.com'])
             ->seeRouteIs('remind.create')
             ->see(trans('validation.exists', ['attribute' => 'email']));
    }

    /** @test */
    public function 비밀번호를_바꾼다()
    {
        $this->createTestStub();

        $this->reset()
             ->notSeeInDatabase('password_resets', ['email' => $this->user->email])
             ->seeRouteIs('root')
             ->see('비밀번호를 바꾸었습니다. 새로운 비밀번호로 로그인하세요.');
    }

    /** @test */
    public function 잘못된_이메일로는_비밀번호를_바꿀_수_없다()
    {
        $this->createTestStub();

        $this->reset(['email' => 'not_existing_email@example.com'])
             ->see(trans('validation.exists', ['attribute' => 'email']));
    }

    /** @test */
    public function 틀린_토큰으로_비밀번호를_바꿀_수_없다()
    {
        $this->createTestStub();

        $this->reset(['token' => str_random(64)])
             ->see('URL이 정확하지 않습니다.');
    }
}