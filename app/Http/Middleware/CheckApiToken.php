<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckApiToken
{
// يعض الاحيان بيهمنى منين الريكوست بتوصل او احدد مين اللى هسيتخدم الاى-بى-اى ريكويست دوول وانه يكون مش اى شخص وذلك من خلال نعمل اى-بى-اى توكن لهذا التطبيق فاى ريكوست سيصل له لابد ان يحمل هذا الاىبى اى
    // فوظيفة هذا الميدلوير انها تفحص اى ريكوست وصلنى من الاى-بى-اى انه يكون فيه هذا التوكن --  والهدف ان مش اى حد اخذ روابط الاي-بى-اى يكون قادر انه ينفذه
    // بفترض او اطلب من الديفولبر يبعتلى هذا التوكن فى هيدر فى الريكوست
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $token = $request->header('x-api-key');
        if ($token !== config('app.api_token')) {
            return response()->json(['message' => 'Invalid API Key'], 400);
        }
        return $next($request);
    }
}
