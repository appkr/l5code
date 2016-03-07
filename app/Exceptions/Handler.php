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
        if (app()->environment('production')) {
            \Slack::send(sprintf(
                "%s \n%s \n\n%s",
                $e->getMessage(),
                $e->getFile(),
                $e->getTraceAsString()
            ));
        }

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
            $title = trans('messages.error.title');
            $description = trans('messages.error.description');

            if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException
                or $e instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException) {
                $statusCode = 404;
                $description = $e->getMessage() ?: trans('messages.error.not_found');
            }

            return response(view('errors.notice', [
                'title' => $title,
                'description' => $description,
            ]), $statusCode);
        }

        return parent::render($request, $e);
    }
}
