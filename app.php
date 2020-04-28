<?php
/**
 * ExchangeRate
 *
 * PHP version 7.4
 *
 * @author Chris Buenafe <christopherbuenafe@gmail.com>
 */

require __DIR__ . '/vendor/autoload.php';

$data = file($argv[1]);

$config = [
    'base_currency' => 'EUR',
    'base_rate' => 1,
    'bin' => [
        'url' => 'https://lookup.binlist.net'
    ],
    'rate' => [
        'url' => 'https://api.exchangeratesapi.io/latest'
    ]
];
$exchange = new App\ExchangeRate($config);

foreach ($data as $line) {
    $txn = json_decode($line);
    if (empty($txn)) {
        // show/log error message here
        continue;
    }
    echo $exchange->process($txn) . "\n";
}
