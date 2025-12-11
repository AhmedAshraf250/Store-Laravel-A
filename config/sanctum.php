<?php
return [


    /**
     * ELEGANT LARAVEL SANCTUM REQUEST LIFECYCLE
     * (Clean and Detailed Flow)
     * ----------------------------------------------------------------------------------
     * This diagram outlines the journey of an HTTP Request hitting an API endpoint
     * protected by the 'auth:sanctum' middleware, focusing on the decision gates.
     *
     * 1. ENTRY POINT & INITIAL PROCESSING
     * ----------------------------------
     * CLIENT (Request with Headers/Body) --> WEB SERVER (NGINX/APACHE) --> LARAVEL INDEX.PHP
     *
     * 2. MIDDLEWARE PIPELINE (API GROUP)
     * ----------------------------------
     * The request enters the Kernel's 'api' middleware group (e.g., Throttling, Bindings).
     *
     * 3. SANCTUM AUTHENTICATION GATE
     * ----------------------------------
     * The request reaches the 'auth:sanctum' middleware.
     *
     * |-------------------------------------------------------------------------------------|
     * | STATE CHECK (Is the request Stateful - SPA/Web?)                                    |
     * |-------------------------------------------------------------------------------------|
     * | IF (Kernel is configured for Stateful requests AND Cookie/Session is present)       |
     * |   --> SUCCESS PATH 1: AUTHENTICATE VIA SESSION (User is retrieved from Session)     |
     * |   --> JUMP TO STEP 5 (SUCCESS)                                                      |
     * |-------------------------------------------------------------------------------------|
     * | ELSE (Default API/Mobile Flow)                                                      |
     * |   --> CONTINUE TO STEP 4 (Stateless/Token Check)                                    |
     * |-------------------------------------------------------------------------------------|
     *
     * 4. BEARER TOKEN PROCESSING
     * ----------------------------
     * REQUEST ---> 'Authorization: Bearer <token>' Header?
     *
     * |-------------------------------------------------------------------------------------|
     * | TOKEN CHECK (Is the Token valid?)                                                   |
     * |-------------------------------------------------------------------------------------|
     * | IF (Bearer Token is found AND Token is valid/not expired AND matched in DB)         |
     * |   --> ACTION: Read 'tokenable_type' and 'tokenable_id'.                             |
     * |   --> SUCCESS PATH 2: AUTHENTICATE VIA TOKEN (Correct Model is instantiated)        |
     * |   --> JUMP TO STEP 5 (SUCCESS)                                                      |
     * |-------------------------------------------------------------------------------------|
     * | ELSE (Token is missing or invalid)                                                  |
     * |   --> FAILURE PATH: Authentication Failed                                           |
     * |   --> JUMP TO STEP 6 (FAILURE)                                                      |
     * |-------------------------------------------------------------------------------------|
     *
     * 5. SUCCESSFUL DISPATCH
     * ----------------------
     * AUTHENTICATED REQUEST ---> LARAVEL ROUTER ---> TARGET CONTROLLER/CLOSURE
     * (Your application code runs with $request->user() available)
     *
     * 6. FAILURE RESPONSE
     * -------------------
     * FAILURE PATH ---> MIDDLEWARE THROWS EXCEPTION
     * RESPONSE: HTTP 401 UNAUTHORIZED is returned to the Client.
     */


    /*
    |--------------------------------------------------------------------------
    | Stateful Domains
    |--------------------------------------------------------------------------
    |
    | Requests from the following domains / hosts will receive stateful API
    | authentication cookies. Typically, these should include your local
    | and production domains which access your API via a frontend SPA.
    |
    */

    'stateful' => explode(',', env('SANCTUM_STATEFUL_DOMAINS', sprintf(
        '%s%s',
        'localhost,localhost:3000,127.0.0.1,127.0.0.1:8000,::1',
        env('APP_URL') ? ',' . parse_url(env('APP_URL'), PHP_URL_HOST) : ''
    ))),

    /*
    |--------------------------------------------------------------------------
    | Sanctum Guards
    |--------------------------------------------------------------------------
    |
    | This array contains the authentication guards that will be checked when
    | Sanctum is trying to authenticate a request. If none of these guards
    | are able to authenticate the request, Sanctum will use the bearer
    | token that's present on an incoming request for authentication.
    |
    */

    'guard' => ['web', 'admin'],

    /*
    |--------------------------------------------------------------------------
    | Expiration Minutes
    |--------------------------------------------------------------------------
    |
    | This value controls the number of minutes until an issued token will be
    | considered expired. If this value is null, personal access tokens do
    | not expire. This won't tweak the lifetime of first-party sessions.
    |
    */

    'expiration' => null,

    /*
    |--------------------------------------------------------------------------
    | Sanctum Middleware
    |--------------------------------------------------------------------------
    |
    | When authenticating your first-party SPA with Sanctum you may need to
    | customize some of the middleware Sanctum uses while processing the
    | request. You may change the middleware listed below as required.
    |
    */

    'middleware' => [
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
    ],

];
