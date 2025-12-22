<?php

namespace App\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use App\Services\CurrencyConvert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;

class CurrencyConverterController extends Controller
{
    public function store(Request $request)
    {

        $request->validate([
            'currency_code' => ['required', 'string', 'size:3', /*'exists:currencies,code'*/],
        ]);

        $currencyCode = $request->post('currency_code');
        $baseCurrency = config('app.currency', 'USD');

        if ($currencyCode === $baseCurrency) {
            Session::put('currency_code', $currencyCode);
            return Redirect::back();
        }

        try {
            $cacheKey = 'currency_rate_' . $currencyCode;
            $rate = Cache::get($cacheKey, 0);
            if (!$rate) {
                $keys = Cache::get('debug_cache_keys', []);
                $keys[] = $cacheKey;
                Cache::put('debug_cache_keys', $keys);

                $converter = app('currency.converter'); // app()->make('currency.converter'); OR App::make('currency.converter');
                $rate = $converter->live($currencyCode, $baseCurrency);
                Cache::put($cacheKey, $rate, now()->addMinutes(60));
            }
        } catch (\Exception $e) {
            // Log the error
            Log::error('Currency conversion failed: ' . $e->getMessage());
            // Optional: fallback to a default rate or previous cached value
            // $rate = Cache::get($cacheKey, 1); 
        }

        Session::put('currency_code', $currencyCode);

        return Redirect::back();
    }
}
