<?php

namespace Test\Http\Controllers;

class UsersControllerTest extends AuthHelper
{
    /** @test */
    public function it_registers_a_user()
    {
        $this->expectsEvents(\App\Events\UserCreated::class);

        $this->register(['name' => 'foo'])
            ->seePageIs('/')
            ->seeInDatabase('users', ['name' => 'foo']);
    }

    /** @test */
    public function it_fails_registration_when_validation_fails()
    {
        $this->register([
            'name' => null,
            'email' => 'malformed.email',
            'password'  => 'short',
            'password_confirmation' => 'not.matching.password',
        ])
            ->see(trans('validation.required', ['attribute' => 'name']))
            ->see(trans('validation.email', ['attribute' => 'email']))
            ->see(trans('validation.confirmed', ['attribute' => 'password']))
            ->seePageIs(route('users.create'));
    }

    /** @test */
    public function it_confirms_user()
    {
        $confirmCode = str_random(60);

        $this->createTestStub(['confirm_code' => $confirmCode, 'activated' => 0]);

        $this->visit(route('users.confirm', $confirmCode))
            ->seePageIs('/')
            ->see($this->user->name . '님, 환영합니다. 가입 확인되었습니다.');

        $this->assertTrue(\App\User::find($this->user->id)->activated);
    }

    /** @test */
    public function it_renders_error_when_code_patten_is_not_allowed()
    {
        $this->createTestStub();
        $this->get(route('users.confirm', 'invalid_confirm_code'))
            ->see('NotFoundHttpException');
    }

    /** @test */
    public function it_fails_confirming_user_when_code_is_not_valid()
    {
        $confirmCode = str_random(60);

        $this->createTestStub(['confirm_code' => $confirmCode, 'activated' => 0]);

        $this->visit(route('users.confirm', str_random(60)))
            ->seePageIs('/')
            ->see('URL이 정확하지 않습니다.');
    }
}
