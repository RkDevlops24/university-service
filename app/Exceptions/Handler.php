<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Routing\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'message' => 'URL not found.'
                ], 404);
            }
        });
    }


    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ThrottleRequestsException) {
            return response()->json([
                'message' => 'Too Many Requests',
            ], 429);
        }

        return parent::render($request, $exception);
    }
}
