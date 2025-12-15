<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use NumberFormatter;

class Currency
{
    // __invoke method is a special method in PHP that runs when calling the class as a function LIKE: Currency(100)

    // ...$parms is used to pass unlimited number of arguments
    // All passed arguments will be collected into a single variable as an array.

    public function __invoke(...$parms)
    {
        return static::format(...$parms);
    }

    public static function format($amount, $currency = null)
    {
        $baseCurrency = config('app.currency', 'USD');

        // initialize formatter 
        $formatter = new NumberFormatter(config('app.locale'), NumberFormatter::CURRENCY);

        if ($currency === null) {
            $currency = Session::get('currency_code', $baseCurrency);
        }

        if ($currency === $baseCurrency) {
            return $formatter->formatCurrency($amount, $currency);
        }

        if ($currency !== $baseCurrency) {
            $rate = Cache::get('currency_rate_' . $currency, 1);
            $amount = $amount * $rate;
        }
        return $formatter->formatCurrency($amount, $currency);
    }
}
