<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

class SetAppLocale
{
    protected $supportedLocales;

    public function __construct()
    {
        $this->supportedLocales = config('app.available_locales');
    }


    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // $locale = $request->segment(1); // http://127.0.0.1:8000/ar/cart => segment(1) => "ar"
        // $locale = $request->segments(); // http://127.0.0.1:8000/ar/cart => segments() => array[ "ar", "cart" ]

        $defaultLocale = config('app.locale', 'en');
        $locale = $request->route('locale'); // prefixing the route with {locale?} parameter

        if (!$locale || !in_array($locale, $this->supportedLocales)) {
            // abort(404);
            $locale = $defaultLocale;
        }

        // if ($locale === $defaultLocale) {
        //     $segments = $request->segments(); // ['en','about']
        //     array_shift($segments);           // ['about']
        //     $path = implode('/', $segments);  // 'about'
        //     return redirect()->to(
        //         '/' . $path
        //     );
        // }

        App::setLocale($locale);

        // The following two lines work together to define a default route parameter for all routes that use this middleware.
        //
        // Since we apply a prefix parameter (like locale) to a route group,
        // it could conflict with existing controllers that already expect specific route parameters.
        // 
        // To avoid parameter collision, we:
        // 1. Set a default value for the parameter so it’s always available.
        // 2. Immediately remove it from the current route parameters list,
        //    so it doesn’t interfere with controller method arguments.
        URL::defaults(['locale' => $locale]); // Laravel هيحقن locale تلقائيًا في أي Route محتاجه
        Route::current()->forgetParameter('locale');

        return $next($request);
    }
}
