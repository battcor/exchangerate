<?php

return [
    'base_currency' => 'EUR',
    'base_rate' => 1,
    'bin' => [
        'provider' => '\App\Provider\Bin\BinListNet',
        'url' => 'https://lookup.binlist.net'
    ],
    'rate' => [
        'provider' => '\App\Provider\Rate\ExchangeRatesApiIo',
        'url' => 'https://api.exchangeratesapi.io/latest'
    ]
];
