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
            ->see('비밀번호를 바꾸는 방법을 담은 이메일을 발송했습니다. 메일박스를 확인해 주세요.');
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
            ->see('비밀번호를 바꾸었습니다. 새로운 비밀번호로 로그인하세요.');
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
            ->see('URL이 정확하지 않습니다.');
    }
}