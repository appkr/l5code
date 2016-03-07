<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
    public function report(Exception $e)
    {
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if (app()->environment('production')) {
            $statusCode = 400;
            $title = '죄송합니다. :(';
            $description = '에러가 발생했습니다.';

            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException
                or $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                $statusCode = 404;
                $description = $e->getMessage() ?: '요청하신 페이지가 없습니다.';
            }

            return response(view('errors.notice', [
                'title' => $title,
                'description' => $description,
            ]), $statusCode);
        }

        return parent::render($request, $e);
    }
}
