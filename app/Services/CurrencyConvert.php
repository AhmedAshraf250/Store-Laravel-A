<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyConvert
{
    private $apiKey;

    protected $baseUrl = 'http://api.currencylayer.com/';
    // protected $from;
    // protected $to;
    // protected $amount;


    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    // LEGACY API ENDPOINT OF freecurrencyconverterapi.com API SERVICE //
    public function convertCurrency(string  $from, string $to, float $amount = 1): float
    {
        $base_url = 'https://freecurrencyconverterapi.com/api/v7';
        $q = "{$from}_{$to}";
        $response = Http::baseUrl($base_url)
            ->get('convert', [
                'q' => $q,
                'compact' => 'ultra',
                'apiKey' => $this->apiKey,
            ]);
        $result = $response->json();
        return $result[$q] * $amount;
        // 'https://freecurrencyconverterapi.com/api/v7/convert?1=USD_PHP,PHP_USD&compact=ultra&apiKey=[YOUR_API_KEY]';
    }

    /**
     *
     * @lookup https://currencylayer.com/quickstart
     */
    public function live(string  $to, string $from = 'USD'): float
    {
        // Make the API request [Using Laravel HTTP Client - Http Facade]
        $response = Http::baseUrl($this->baseUrl)
            // ->withHeaders([
            //     'Content-Type' => 'application/json',
            //     'Access-Key' => $this->apiKey,
            //     'authorization' => 'Bearer ' . $this->apiKey,
            // ]);
            ->get('live', [
                'access_key' => $this->apiKey,
                'currencies' => strtoupper($to), // ['GBP', 'EUR'],
                'source' => strtoupper($from), // 'USD',
                // 'format' => 1
            ]); // 'http://api.currencylayer.com/live?access_key=this->apiKey&currencies=GBP,EUR&source=USD');

        if ($response->failed()) {
            throw new \Exception('Currency API request failed: ' . $response->status());
        }

        $result = $response->json();
        // dd($result);
        //      array:6 [▼ // app\Services\CurrencyConvert.php:58
        //          "success" => true
        //          "terms" => "https://currencylayer.com/terms"
        //          "privacy" => "https://currencylayer.com/privacy"
        //          "timestamp" => 1765714507
        //          "source" => "USD"
        //          "quotes" => array:1 [▼
        //              "USDEUR" => 0.851404
        //          ]
        //      ]

        if (!$result['success'] ?? false) {
            throw new \Exception('Currency API error: ' . ($result['error']['info'] ?? 'Unknown error'));
        }

        $key = "{$from}{$to}"; // like: USDEUR
        return $result['quotes'][$key] ?? throw new \Exception("Rate not found for {$key}");
    }

    public function convert(float $amount, string  $from, string $to) //: float
    {

        return Http::baseUrl($this->baseUrl)
            ->get('convert', [
                'access_key' => $this->apiKey,
                'from' => $from,
                'to' => $to,
                'amount' => $amount,
                // 'format' => 1
            ]);
    }


    public function currencyLive()
    {

        $endpoint = 'live';
        $access_key = $this->apiKey;

        // Initialize CURL:
        $ch = curl_init('https://api.currencylayer.com/' . $endpoint . '?access_key=' . $access_key . '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Store the data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $exchangeRates = json_decode($json, true);

        // Access the exchange rate values, e.g. GBP:
        echo $exchangeRates['quotes']['USDGBP'];
    }

    public function currencyConvert()
    {
        // set API Endpoint, access key, required parameters
        $endpoint = 'convert';
        $access_key = $this->apiKey;

        $from = 'USD';
        $to = 'EUR';
        $amount = 10;

        // initialize CURL:
        $ch = curl_init('https://api.currencylayer.com/' . $endpoint . '?access_key=' . $access_key . '&from=' . $from . '&to=' . $to . '&amount=' . $amount . '');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // get the (still encoded) JSON data:
        $json = curl_exec($ch);
        curl_close($ch);

        // Decode JSON response:
        $conversionResult = json_decode($json, true);

        // access the conversion result
        echo $conversionResult['result'];
    }
}
