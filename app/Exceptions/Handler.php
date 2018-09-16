<?php

namespace App\Exceptions;

use Embed\Exceptions\InvalidUrlException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        AuthenticationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
        TokenMismatchException::class
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return
     * @throws Exception
     */
    public function report(Exception $exception)
    {
        if (app()->bound('sentry') && $this->shouldntReport($exception)){
            app('sentry')->captureException($exception);
        }
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if ($request->expectsJson()){
            if ($exception instanceof TokenMismatchException) {
                return res(401);
            }

            if ($exception instanceof InvalidUrlException) {
                return res(400, 'Invalid URL');
            }

            if ($exception instanceof InvalidUrlException) {
                return res(403, 'Invalid URL');
            }

            // 404 not found
            if ($exception instanceof ModelNotFoundException || $exception instanceof NotFoundHttpException) {
                return res(404);
            }

            // not allowed method
            if ($exception instanceof MethodNotAllowedHttpException) {
                return res(405);
            }

            // service unavailable
            if ($exception instanceof MaintenanceModeException) {
                return res(503);
            }

            if ($exception instanceof HttpException) {
                return res($exception->getStatusCode());
            }
        }
        if ($exception instanceof TokenMismatchException) {
            return redirect()->guest('login');
        }
        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()){
            return res(401);
        }
        return redirect()->guest('login');
    }
}
