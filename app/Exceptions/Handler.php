<?php

namespace App\Exceptions;

use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        InvalidOrderException::class,
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
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
     *
     * @return void
     */
    public function register()
    {
        /*
        |--------------------------------------------------------------------------
        | Exception Handling: Reporting vs Rendering
        |--------------------------------------------------------------------------
        |
        | Exception handling in Laravel has two main responsibilities:
        |
        | 1️ Reporting:
        |    - Decides IF and HOW an exception should be logged or reported.
        |    - You can customize logging channels, formats, and conditions.
        |
        | 2️ Rendering:
        |    - Controls HOW the exception is displayed to the user.
        |    - Returns the final response (JSON, redirect, view, etc).
        |
        */

        $this->reportable(function (QueryException $e) {

            if ((int) $e->getCode() === 23000) {
                Log::channel('sql')->error($e->getMessage(), [
                    'sql' => $e->getSql(),
                    'bindings' => $e->getBindings(),
                ]);

                return true; // The exception has been handled, Laravel will STOP the default logging behavior
            }

            return false; // Continue with Laravel’s default logging
        });
        // return true  => أنا تعاملت مع اللوج → Laravel توقف
        // return false => كمّل اللوج الافتراضي

        /*
        |--------------------------------------------------------------------------
        | Custom Exception Rendering
        |--------------------------------------------------------------------------
        |
        | Rendering controls how the exception is returned to the client.
        |
        | - We can target specific exception types
        | - Laravel automatically injects the current Request
        | - A Response MUST be returned
        |
        | Common exceptions you may customize:
        | ValidationException, QueryException, ModelNotFoundException, HttpResponseException, etc.
        |
        */
        $this->renderable(function (QueryException $e, Request $request) {
            $e->getCode() == 2300 ? $message = 'Foreign key constraint fails' : $message = $e->getMessage();

            if ($request->expectsJson()) {
                return response()->json(['message' => $message], 400);
            }

            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => $e->getMessage()])
                ->with('info', $message);
        });
    }
}
