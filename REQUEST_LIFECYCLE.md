# Laravel Request Lifecycle - Complete Guide


╔═════════════════════════════════════════════════════════════════════════╗
║                    LARAVEL REQUEST LIFECYCLE DIAGRAM                    ║
╚═════════════════════════════════════════════════════════════════════════╝

┌─────────────────────────────────────────────────────────────────────────┐
│                      STEP 1: ENTRY POINT (index.php)                    │
├─────────────────────────────────────────────────────────────────────────┤
│  • Application entry point for all HTTP requests                        │
│  • Loads Composer's autoloader (vendor/autoload.php)                    │
│  • Bootstraps Laravel application (bootstrap/app.php)                   │
│  • Creates the application instance                                     │
│                                                                         │
│  File: public/index.php                                                 │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                  STEP 2: HTTP KERNEL INITIALIZATION                     │
├─────────────────────────────────────────────────────────────────────────┤
│  • Creates Application Container (IoC Container)                        │
│  • Registers core Service Providers                                     │
│  • Creates HTTP Kernel instance                                         │
│  • Binds important interfaces to implementations                        │
│                                                                         │
│  File: app/Http/Kernel.php                                              │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                    STEP 3: SERVICE PROVIDERS BOOT                       │
├─────────────────────────────────────────────────────────────────────────┤
│  • Executes register() method for all Service Providers                 │
│  • Executes boot() method for all Service Providers                     │
│  • Registers bindings, singletons, and aliases                          │
│  • Loads routes, views, translations, and configurations                │
│  • Publishes resources and assets                                       │
│                                                                         │
│  Files: app/Providers/*.php, config/app.php                             │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                      STEP 4: REQUEST CAPTURE                            │
├─────────────────────────────────────────────────────────────────────────┤
│  • Creates Illuminate\Http\Request object from PHP globals              │
│  • Captures $_GET, $_POST, $_SERVER, $_FILES, $_COOKIE                  │
│  • Processes HTTP headers and method                                    │
│  • Parses request body (JSON, form-data, etc.)                          │
│                                                                         │
│  Class: Illuminate\Http\Request                                         │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────┐
│               STEP 5: GLOBAL MIDDLEWARE (BOOTSTRAP)                     │
├─────────────────────────────────────────────────────────────────────────┤
│  ▸ TrustProxies         → Configure trusted proxies                     │
│  ▸ HandleCors           → Handle CORS headers and preflight             │
│  ▸ PreventRequestsDuringMaintenance → Check maintenance mode            │
│  ▸ ValidatePostSize     → Validate POST request size                    │
│  ▸ TrimStrings          → Trim whitespace from input                    │
│  ▸ ConvertEmptyStringsToNull → Convert empty strings to null            │
│                                                                         │
│  Property: $middleware in app/Http/Kernel.php                           │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                       STEP 6: ROUTER DISPATCH                           │
├─────────────────────────────────────────────────────────────────────────┤
│  • Matches request URI to defined routes                                │
│  • Identifies controller/closure action                                 │
│  • Extracts route parameters from URL                                   │
│  • Applies route constraints and regex patterns                         │
│  • Determines route middleware stack                                    │
│                                                                         │
│  Files: routes/web.php, routes/api.php                                  │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                    STEP 7: ROUTE MIDDLEWARE STACK                       │
├─────────────────────────────────────────────────────────────────────────┤
│  ▸ SubstituteBindings   → Resolve Route Model Binding                   │
│  ▸ Authenticate         → Verify user authentication                    │
│  ▸ Authorize            → Check user permissions/policies               │
│  ▸ ThrottleRequests     → Apply rate limiting                           │
│  ▸ VerifyCsrfToken      → Validate CSRF token for state-changing ops    │
│  ▸ Custom Middleware    → Any custom middleware defined                 │
│                                                                         │
│  Property: $middlewareGroups & $routeMiddleware in Kernel.php           │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                     STEP 8: CONTROLLER RESOLUTION                       │
├─────────────────────────────────────────────────────────────────────────┤
│  • Resolves controller via Service Container                            │
│  • Performs Dependency Injection for constructor                        │
│  • Executes controller middleware if defined                            │
│  • Validates Form Requests (if applicable)                              │
│  • Resolves method parameters (DI, Route Model Binding)                 │
│                                                                         │
│  Files: app/Http/Controllers/*.php                                      │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                    STEP 9: BUSINESS LOGIC EXECUTION                     │
├─────────────────────────────────────────────────────────────────────────┤
│  • Executes controller action/method                                    │
│  • Calls Services/Repositories                                          │
│  • Interacts with Database (Eloquent ORM/Query Builder)                 │
│  • Handles file uploads/storage operations                              │
│  • Calls external APIs                                                  │
│  • Dispatches Jobs, Events, Notifications                               │
│  • Processes business rules and logic                                   │
│                                                                         │
│  Files: app/Services/*, app/Models/*, app/Repositories/*                │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                     STEP 10: RESPONSE GENERATION                        │
├─────────────────────────────────────────────────────────────────────────┤
│  • Returns View (Blade template rendering)                              │
│  • OR Returns JSON response (for APIs)                                  │
│  • OR Returns Redirect response                                         │
│  • OR Returns File download                                             │
│  • OR Returns custom Response object                                    │
│  • Converts return value to Illuminate\Http\Response                    │
│                                                                         │
│  Files: resources/views/*.blade.php                                     │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                  STEP 11: MIDDLEWARE (RESPONSE PHASE)                   │
├─────────────────────────────────────────────────────────────────────────┤
│  • Executes middleware in REVERSE order (inside-out)                    │
│  • Modifies response before sending to client                           │
│  • Adds/modifies HTTP headers                                           │
│  • Sets cookies                                                         │
│  • Compresses content                                                   │
│  • Applies security headers                                             │
│                                                                         │
│  Note: Each middleware's "after" logic runs here                        │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                      STEP 12: SEND RESPONSE                             │
├─────────────────────────────────────────────────────────────────────────┤
│  • Sends HTTP headers to browser                                        │
│  • Sends response content/body                                          │
│  • Sets cookies in browser                                              │
│  • Flushes output buffers                                               │
│  • Closes connection to client                                          │
│                                                                         │
│  Method: Response::send()                                               │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
┌─────────────────────────────────────────────────────────────────────────┐
│                    STEP 13: TERMINATE MIDDLEWARE                        │
├─────────────────────────────────────────────────────────────────────────┤
│  • Executes terminate() method in middleware (if defined)               │
│  • Performs cleanup tasks AFTER response sent                           │
│  • Saves session data                                                   │
│  • Writes logs                                                          │
│  • Sends queued emails/notifications                                    │
│  • Does NOT affect response time perceived by user                      │
│                                                                         │
│  Method: Middleware::terminate($request, $response)                     │
└────────────────────────────────┬────────────────────────────────────────┘
                                 │
                                 ▼
                         ┌───────────────────┐
                         │   REQUEST CYCLE   │
                         │     COMPLETE      │
                         └───────────────────┘


═══════════════════════════════════════════════════════════════════════════

                           ADDITIONAL CONCEPTS

═══════════════════════════════════════════════════════════════════════════

┌─────────────────────────────────────────────────────────────────────────┐
│                       MIDDLEWARE FLOW DIAGRAM                           │
├─────────────────────────────────────────────────────────────────────────┤
│                                                                         │
│   REQUEST PHASE (Going Down):                                           │
│   ───────────────────────────                                           │
│   Request → Middleware A → Middleware B → Middleware C → Controller     │
│                                                                         │
│   RESPONSE PHASE (Coming Back Up):                                      │
│   ─────────────────────────────────────                                 │
│   Response ← Middleware A ← Middleware B ← Middleware C ← Controller    │
│                                                                         │
│   Example Middleware Implementation:                                    │
│   ────────────────────────────────────                                  │
│   public function handle($request, Closure $next)                       │
│   {                                                                     │
│       // Before controller (Request phase)                              │
│       $request->merge(['timestamp' => now()]);                          │
│                                                                         │
│       $response = $next($request); // Pass to next middleware           │
│                                                                         │
│       // After controller (Response phase)                              │
│       $response->header('X-Custom-Header', 'Value');                    │
│                                                                         │
│       return $response;                                                 │
│   }                                                                     │
└─────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────┐
│                      SERVICE CONTAINER (IoC)                            │
├─────────────────────────────────────────────────────────────────────────┤
│  • Manages class dependencies and performs dependency injection         │
│  • Automatically resolves dependencies throughout request lifecycle     │
│  • Supports binding, singleton, instance, and contextual binding        │
│                                                                         │
│  Example:                                                               │
│  ────────                                                               │
│  // Binding                                                             │
│  app()->bind(PaymentGateway::class, StripeGateway::class);              │
│                                                                         │
│  // Auto-injection in controller                                        │
│  public function __construct(PaymentGateway $gateway)                   │
│  {                                                                      │
│      $this->gateway = $gateway; // Automatically resolved!              │
│  }                                                                      │
└─────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────┐
│                       PIPELINE PATTERN                                  │
├─────────────────────────────────────────────────────────────────────────┤
│  • Laravel uses Pipeline pattern to pass Request through middleware     │
│  • Each middleware receives request and passes to next in chain         │
│  • Allows for clean, modular request handling                           │
│                                                                         │
│  Implementation:                                                        │
│  ───────────────                                                        │
│  app(Pipeline::class)                                                   │
│      ->send($request)                                                   │
│      ->through($middleware)                                             │
│      ->then(function ($request) {                                       │
│          return $this->router->dispatch($request);                      │
│      });                                                                │
└─────────────────────────────────────────────────────────────────────────┘

┌─────────────────────────────────────────────────────────────────────────┐
│                      EXCEPTION HANDLING                                 │
├─────────────────────────────────────────────────────────────────────────┤
│  • Any exception at ANY stage is caught by Exception Handler            │
│  • Converts exceptions to appropriate HTTP responses                    │
│  • Logs errors based on severity                                        │
│  • Can send error notifications                                         │
│  • Returns user-friendly error pages/JSON                               │
│                                                                         │
│  File: app/Exceptions/Handler.php                                       │
│                                                                         │
│  Methods:                                                               │
│  ─────────                                                              │
│  • register()  → Register exception handlers                            │
│  • report()    → Log/report exceptions                                  │
│  • render()    → Convert exception to HTTP response                     │
└─────────────────────────────────────────────────────────────────────────┘


═══════════════════════════════════════════════════════════════════════════

                           PRACTICAL EXAMPLE

═══════════════════════════════════════════════════════════════════════════

// User visits: https://example.com/users/123

// ──────────────────────────────────────────────────────────────────────

// STEP 1-4: index.php receives request, boots Laravel, creates Request

// STEP 5: Global Middleware
// → TrustProxies (if behind load balancer)
// → HandleCors (if API)
// → TrimStrings (removes whitespace from inputs)

// STEP 6: Router matches route
Route::get('/users/{id}', [UserController::class, 'show'])
    ->middleware('auth');

// STEP 7: Route Middleware
// → 'auth' middleware checks if user is logged in
// → SubstituteBindings resolves {id} to User model (Route Model Binding)

// STEP 8-9: Controller executes
class UserController extends Controller
{
    public function show(User $user) // $user auto-injected!
    {
        // Business logic here
        $user->load('posts', 'comments');
        
        return view('users.show', compact('user'));
    }
}

// STEP 10: Blade renders view to HTML
// resources/views/users/show.blade.php

// STEP 11: Middleware response phase
// → Each middleware can modify response

// STEP 12: Response sent to browser
// → HTML, headers, cookies sent to client

// STEP 13: Terminate phase
// → Session saved
// → Logs written
// → Queued jobs processed


═══════════════════════════════════════════════════════════════════════════

                          KEY FILES REFERENCE

═══════════════════════════════════════════════════════════════════════════

Entry Point:
├── public/index.php                    (Application entry point)
└── bootstrap/app.php                   (Application bootstrap)

Kernel & Middleware:
├── app/Http/Kernel.php                 (HTTP Kernel configuration)
└── app/Http/Middleware/*.php           (Custom middleware)

Routing:
├── routes/web.php                      (Web routes)
├── routes/api.php                      (API routes)
├── routes/console.php                  (Artisan commands)
└── routes/channels.php                 (Broadcasting channels)

Service Providers:
├── app/Providers/AppServiceProvider.php
├── app/Providers/RouteServiceProvider.php
├── app/Providers/AuthServiceProvider.php
└── config/app.php                      (Provider registration)

Controllers & Logic:
├── app/Http/Controllers/*.php          (Controllers)
├── app/Models/*.php                    (Eloquent models)
├── app/Services/*.php                  (Business logic)
└── app/Repositories/*.php              (Data access layer)

Views & Responses:
├── resources/views/*.blade.php         (Blade templates)
└── app/Http/Resources/*.php            (API resources)

Exception Handling:
└── app/Exceptions/Handler.php          (Global exception handler)


═══════════════════════════════════════════════════════════════════════════

                            PERFORMANCE TIPS

═══════════════════════════════════════════════════════════════════════════

1. Minimize Middleware Stack
   → Only use necessary middleware for each route
   → Remove unused global middleware

2. Optimize Service Providers
   → Use deferred providers for services not needed on every request
   → Lazy load heavy dependencies

3. Use Route Caching
   → php artisan route:cache (production only)
   → Dramatically speeds up route matching

4. Use Config Caching
   → php artisan config:cache (production only)
   → Loads all config files at once

5. Optimize Autoloader
   → composer install --optimize-autoloader --no-dev

6. Use OPcache
   → Enable PHP OPcache in production
   → Caches compiled PHP bytecode

7. Leverage Terminate Phase
   → Move non-critical tasks to terminate() method
   → Doesn't affect perceived response time


═══════════════════════════════════════════════════════════════════════════

                            DEBUGGING TIPS

═══════════════════════════════════════════════════════════════════════════

1. Use Laravel Telescope
   → Monitor requests, queries, jobs, exceptions in real-time

2. Enable Query Logging
   DB::enableQueryLog();
   // ... your code ...
   dd(DB::getQueryLog());

3. Use Ray / Dump / Log
   ray($request);              // Ray debug tool
   dump($request);             // Symfony VarDumper
   logger('Message', $data);   // Laravel logger

4. Inspect Middleware Stack
   Route::getMiddleware();     // Get middleware for route

5. Check Service Provider Boot Order
   php artisan about           // Shows loaded providers

6. Profile Performance
   → Use Laravel Debugbar
   → Use Blackfire.io
   → Monitor with New Relic / Datadog


═══════════════════════════════════════════════════════════════════════════

Created for Laravel 10.x / 11.x
Last Updated: 2024
Documentation: https://laravel.com/docs/lifecycle

═══════════════════════════════════════════════════════════════════════════