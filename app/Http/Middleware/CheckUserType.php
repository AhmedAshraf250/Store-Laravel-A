<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$types)
    {
        /**
         * Middleware execution flow in Laravel:
         * 
         * 1. Code BEFORE `$next($request)` → runs on the way IN (before controller)
         * 2. `$next($request)`             → sends request forward to next middlewares + controller
         * 3. Code AFTER `$next($request)`  → runs on the way OUT (after controller)
         * 
         * - This means: 
         * You can MODIFY the final Response (headers, content, status...), even AFTER the controller has finished and returned its response!
         * 
         * Very powerful for: CORS, caching headers, response compression,
         * adding footers, encryption, logging, etc.
         */


        /**
         * Middleware runs before (and sometimes after) a request reaches the controller.
         * The handle() method always receives two arguments:
         *   - $request : the current HTTP request (passed automatically by Laravel)
         *   - $next    : a callback that forwards the request to the next middleware or controller
         */

        $user = $request->user();
        if (!$user) {
            return redirect()->route('login');
        }
        if (!in_array($user->type, $types)) {
            abort(403);
        }
        return $next($request);



        //How to Modify Response even AFTER the controller has finished and returned its response
        /*
        public function handle(Request $request, Closure $next)
        {
                // code here runs BEFORE controller

            $response = $next($request);

                // code here runs AFTER controller → perfect place to modify response
                // Example: $response->header('X-Custom', 'value');
                //          $response->setContent(...);
                //          $oldContent = $response->getContent();$newContent ='new content';
                //          $response->setContent($newContent);

            return $response;
        }
         */
    }
}
