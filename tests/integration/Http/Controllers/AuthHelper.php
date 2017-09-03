<?php

namespace Test\Http\Controllers;

use App\Article;
use App\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class AuthHelper extends \TestCase
{
    use DatabaseTransactions;

    /**
     * The base URL to use while testing the application.
     *
     * @var string
     */
    protected $baseUrl;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var Article
     */
    protected $article;

    /**
     * @var array
     */
    protected $userPayload = [
        'name' => 'foo',
        'email' => 'foo@bar.com',
        'password' => 'password'
    ];

    /**
     * Set up.
     */
    public function setUp()
    {
        parent::setUp();
        $this->baseUrl = config('project.url');
    }

    /**
     * Visit login page and attempt login.
     *
     * @param array $overrides
     * @return mixed
     */
    public function login($overrides = [])
    {
        return $this->visit(route('sessions.create'))
            ->submitForm(
                trans('auth.sessions.title'),
                array_merge([
                    'email' => $this->user->email,
                    'password' => 'password',
                ], $overrides)
            );
    }

    /**
     * Visit login route.
     *
     * @return mixed
     */
    public function logout()
    {
        return $this->visit(route('sessions.destroy'));
    }

    /**
     * Visit signup page and attempt user registration.
     *
     * @param array $overrides
     * @return $this
     */
    public function register($overrides = [])
    {
        return $this->visit(route('users.create'))
            ->submitForm(
                trans('auth.users.send_registration'),
                array_merge(
                    $this->userPayload,
                    ['password_confirmation' => $this->userPayload['password']],
                    $overrides
                )
            );
    }

    /**
     * Visit password remind page and attempt the password remind.
     *
     * @param array $overrides
     * @return $this
     */
    public function remind($overrides = [])
    {
        return $this->visit(route('remind.create'))
            ->submitForm(
                trans('auth.passwords.send_reminder'),
                [
                    'email' => array_key_exists('email', $overrides)
                        ? $overrides['email']
                        : $this->user->email
                ]
            );
    }

    /**
     * Visit password reset page and attempt reset.
     *
     * @param array $overrides
     * @return $this
     */
    public function reset($overrides = [])
    {
        $email = $this->user->email;
        $token = str_random(64);

        \DB::table('password_resets')->insert([
            'email' => $email,
            'token' => $token,
            'created_at' => \Carbon\Carbon::now()->toDateTimeString()
        ]);

        // Override token
        $token = array_key_exists('token', $overrides)
            ? $overrides['token'] : $token;

        return $this->visit(route('reset.create', ['token' => $token]))
            ->submitForm(
                trans('auth.passwords.send_reset'),
                array_merge([
                    'email' => array_key_exists('email', $overrides)
                        ? $overrides['email'] : $email,
                    'password' => 'password',
                    'password_confirmation' => 'password',
                    'token' => $token
                ], $overrides)
            );
    }

    /**
     * Stubbing test data.
     *
     * @param array $overrides
     */
    protected function createTestStub($overrides = [])
    {
        $this->user = empty($overrides)
            ? factory(User::class)->create()
            : factory(User::class)->create($overrides);

        $this->article = factory(Article::class)->create([
            'title' => 'title',
            'user_id' => $this->user->id,
            'content' => 'description',
        ]);
    }
}