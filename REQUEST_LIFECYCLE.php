<?php

/**
 * 
public/index.php
→ vendor/autoload.php
→ bootstrap/app.php
   → new Application($_ENV['APP_BASE_PATH'] ?? dirname(__DIR__))
   → $app->singleton(Kernel::class, HttpKernel::class)
   → $app->singleton(ConsoleKernel::class, ArtisanKernel::class)
   → $app->singleton(ExceptionHandler::class, AppExceptionHandler::class)
   → return $app;
→ Kernel@handle()  // هنا بيبدأ يحمل الـ .env والـ config والـ providers



 * ===================================================================
 *                LARAVEL REQUEST LIFECYCLE - FULL VISUAL MAP
 *                   (Laravel 11 - Updated 2025)
 * ===================================================================
 *
 * Every HTTP request passes through these exact 9 stages:
 *
 *  1. public/index.php
 *        ↓
 *  2. Http/Kernel → handle($request)
 *        ↓
 *  3. Service Providers → register()   (only AppServiceProvider in L11)
 *        ↓
 *  4. Service Providers → boot()       (all registered providers)
 *        ↓
 *  5. Routing → Match route (web.php / api.php)
 *        ↓
 *  6. Middleware (global → group → route)
 *        ↓
 *  7. Controller → __construct → middleware → action method
 *        ↓
 *  8. Response created (view, json, redirect, etc.)
 *        ↓
 *  9. Kernel → terminate middleware + terminate() callbacks
 *
 * ===================================================================
 *                        DETAILED FLOW (ASCII)
 * ===================================================================
 *
 *  ┌───────────────────────────────────────────────────────────────┐
 *  │ 1. public/index.php                                           │
 *  │    • Loads composer autoloader                                │
 *  │    • require bootstrap/app.php                                │
 *  │    • $app    = new Illuminate\Foundation\Application          │
 *  │    • $kernel = $app->make(Illuminate\Contracts\Http\Kernel::class) │
 *  │    • $response = $kernel->handle($request)                    │
 *  │    • $response->send()                                        │
 *  │    • $kernel->terminate($request, $response)                  │
 *  └───────────────────────────────────────────────────────────────┘
 *
 *  ┌───────────────────────────────────────────────────────────────┐
 *  │ 2. app/Http/Kernel.php → handle($request)                     │
 *  │    • $request = Request::capture()                            │
 *  │    • Sends request through Pipeline (middleware + routing)    │
 *  └───────────────────────────────────────────────────────────────┘
 *
 *  ┌───────────────────────────────────────────────────────────────┐
 *  │ 3. ServiceProvider@register()                                 │
 *  │    • Runs ONLY in AppServiceProvider (Laravel 11)             │
 *  │    • Perfect for: bindings, singletons, container aliases     │
 *  │    • Example: $this->app->singleton(Repo::class, ...)         │
 *  └───────────────────────────────────────────────────────────────┘
 *
 *  ┌───────────────────────────────────────────────────────────────┐
 *  │ 4. ServiceProvider@boot()                                     │
 *  │    • Runs in ALL providers after register() phase             │
 *  │    • Perfect for:                                             │
 *  │       - View composers / shared data                          │
 *  │       - Custom validation rules                               │
 *  │       - Event listeners                                       │
 *  │       - Macros, Blade directives                              │
 *  └───────────────────────────────────────────────────────────────┘
 *
 *  ┌───────────────────────────────────────────────────────────────┐
 *  │ 5. Routing (RouteServiceProvider)                             │
 *  │    • Loads web.php, api.php, etc.                             │
 *  │    • Matches URL → Controller@method or Closure               │
 *  └───────────────────────────────────────────────────────────────┘
 *
 *  ┌───────────────────────────────────────────────────────────────┐
 *  │ 6. Middleware Pipeline                                        │
 *  │    • Global middleware (TrustProxies, CheckForMaintenance...) │
 *  │    • Route group middleware (web, api)                        │
 *  │    • Route-specific middleware (auth, verified, etc.)        │
 *  └────────────────────────────────––––––––––––––––––––––––––––───┘
 *
 *  ┌───────────────────────────────────────────────────────────────┐
 *  │ 7. Controller Execution                                       │
 *  │    • Controller __construct()                                 │
 *  │    • Controller middleware (if defined)                       │
 *  │    • Target method runs → returns Response                    │
 *  └───────────────────────────────────────────────────────────────┘
 *
 *  ┌───────────────────────────────────────────────────────────────┐
 *  │ 8. Response Preparation                                       │
 *  │    • View → rendered → converted to Symfony Response          │
 *  │    • JSON → encoded → Response                                │
 *  │    • Redirect → RedirectResponse                              │
 *  └───────────────────────────────────────────────────────────────┘
 *
 *  ┌───────────────────────────────────────────────────────────────┐
 *  │ 9. Terminate Phase (after response sent)                      │
 *  │    • Runs terminate() middleware                              │
 *  │    • Runs $provider->terminate() if defined                   │
 *  │    • Ideal for: queue jobs, logging, cache warmup             │
 *  └───────────────────────────────────────────────────────────────┘
 *
 * ===================================================================
 *                   PRO TIPS FOR DEBUGGING & HOOKS
 * ===================================================================
 *
 *  • Want something on EVERY request?       → AppServiceProvider@register()
 *  • Need view data on all pages?           → AppServiceProvider@boot()
 *  • Run code AFTER response is sent?       → terminate() middleware
 *  • Bind repositories / services?          → AppServiceProvider@register()
 *  • Custom validation / Blade directives? → AnyProvider@boot()
 *
 * ===================================================================
 *  Copy-paste this anywhere: AppServiceProvider, README.md, or docs
 *  100% English, clean, professional, and HR-ready!
 * ===================================================================
 */
