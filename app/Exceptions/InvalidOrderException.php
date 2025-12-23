<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;

class InvalidOrderException extends Exception
{

    /*
            ┌──────────────────────────┐
            │  throw CustomException   │
            └───────────┬──────────────┘
                        │
                        ▼
        ┌────────────────────────────────────────┐
        │ app/Exceptions/Handler.php             │
        │                                        │
        │ public function register()             │
        │ {                                      │
        │   $this->reportable(...)   ← (A)       │
        │   $this->renderable(...)   ← (B)       │
        │ }                                      │
        └───────────────┬────────────────────────┘
                        │
                        ▼
        ┌─────────────────────────────────────────────┐
        │ (A) REPORTING PIPELINE                      │
        │                                             │
        │ Handler::shouldntReport()                   │
        │   └─ checks $dontReport[]                   │
        │                                             │
        │ Handler::report()                           │
        │                                             │
        │ Handler::reportable() callbacks             │
        │   return true  → STOP reporting             │
        │   return false → CONTINUE                   │
        │                                             │
        │ Exception::report()   (if exists)           │
        │   └─ return value IGNORED                   │
        │                                             │
        │ Default Logger (laravel.log, sentry, etc.)  │
        └───────────────────┬─────────────────────────┘
                            │
                            ▼
        ┌─────────────────────────────────────────────┐
        │ (B) RENDERING PIPELINE                      │
        │                                             │
        │ Handler::render()                           │
        │                                             │
        │ Handler::renderable() callbacks             │
        │   └─ match exception → return Response      │
        │                                             │
        │ Exception::render()   (if exists)           │
        │                                             │
        │ Default Laravel Rendering                   │
        │   (HTML error page / JSON error)            │
        └───────────────────────┬─────────────────────┘
                                │
                                ▼
                ┌──────────────────────────────┐
                │        HTTP RESPONSE         │
                └──────────────────────────────┘

    */

    /**
     * Report the exception.
     *
     * @return bool
     */
    public function report()
    {
        // Log::channel('stack')->error($this->getMessage());
        return true; // The exception has been handled, Laravel will STOP the default logging behavior
    }

    public function render(Request $request)
    {
        return Redirect::route('home')
            ->withInput()
            ->withErrors([
                'message' => $this->getMessage()
            ])
            ->with('info', $this->getMessage());
    }
}
