<?php

// don't forget to run "composer dump-autoload" after each adding any functions

use Illuminate\Support\Facades\Http;


if (!function_exists('gen_ran_str')) {
    function gen_ran_str($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[random_int(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    function safe_image($url, $fallback = null)
    {
        // لو فاضي أو null → fallback فورًا
        if (empty($url)) {
            return $fallback ?? asset('storage/uploads/product.png');
        }

        try {
            // طلب HEAD بوقت قصير جدًا عشان الصفحة ماتتأخرش
            $response = Http::timeout(1)->head($url);

            if ($response->successful()) {
                return $url;
            }
        } catch (\Exception $e) {
            // any problem → return fallback
            return $fallback ?? asset('storage/uploads/product.png');
        }

        return $fallback ?? asset('storage/uploads/product.png');
    }
}
