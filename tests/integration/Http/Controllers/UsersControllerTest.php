<?php

namespace Test\Http\Controllers;

class UsersControllerTest extends AuthHelper
{
    /** @test */
    public function 회원가입한다()
    {
        $this->expectsEvents(\App\Events\UserCreated::class);

        $this->register(['name' => 'foo'])
            ->seeRouteIs('root')
            ->seeInDatabase('users', ['name' => 'foo']);
    }

    /** @test */
    public function 사용자_입력값이_유효하지_않으면_회원가입_실패한다()
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
            ->seeRouteIs('users.create');
    }

    /** @test */
    public function 회원가입_확인한다()
    {
        $confirmCode = str_random(60);

        $this->createTestStub(['confirm_code' => $confirmCode, 'activated' => 0]);

        $this->visit(route('users.confirm', $confirmCode))
            ->seeRouteIs('home')
            ->see(
                trans('auth.users.info_confirmed', ['name' => $this->user->name])
            );

        $this->assertTrue(\App\User::find($this->user->id)->activated);
    }

    /** @test */
    public function 잘못된_회원가입_확인_코드에_페이지_오류난다()
    {
        $this->createTestStub();
        $this->get(route('users.confirm', 'invalid_confirm_code'))
            ->see('NotFoundHttpException');
    }

    /** @test */
    public function 잘못된_회원가입_확인_코드로_가입_확인할_수_없다()
    {
        $confirmCode = str_random(60);

        $this->createTestStub(['confirm_code' => $confirmCode, 'activated' => 0]);

        $this->visit(route('users.confirm', str_random(60)))
            ->seeRouteIs('root')
            ->see(
                trans('auth.users.error_wrong_url')
            );
    }
}