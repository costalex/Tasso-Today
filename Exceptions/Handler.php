<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
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
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        if (app()->bound('sentry') && $this->shouldReport($exception)) {
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
	    if ($exception instanceof \Symfony\Component\HttpKernel\Exception\NotFoundHttpException)
		    return response()->view('marketplace', ['message' => $exception->getMessage()], 404);
	    else if ($exception instanceof UnauthorizedHttpException)
		    return new \Illuminate\Http\Response($exception->getMessage(), 401, ['WWW-Authenticate' => 'Basic']);
	    else if ($exception instanceof \Symfony\Component\HttpKernel\Exception\HttpException)
		    return response(['message' => $exception->getMessage()], $exception->getStatusCode());
	    else if ($exception instanceof \Stripe\Error\Base || $exception instanceof \Stripe\Error\ApiConnection   ||
		    $exception instanceof \Stripe\Error\Authentication || $exception instanceof \Stripe\Error\InvalidRequest  ||
		    $exception instanceof \Stripe\Error\RateLimit || $exception instanceof \Stripe\Error\Card)
		    return response(['message' => $exception->getMessage()], 400);
    }
}