# ExchangeRate

This class calculates the commision of a transaction.

Retrieves BIN information and exchange rates from providers.


## Install

```
$ git clone git@github.com:battcor/exchangerate.git
$ cd exchangerate/
$ composer install
```

## Usage

```
$ php app.php input.txt
1
0.93
1.73
2.4
45.84
```

## Configuration

[config.php](https://github.com/battcor/exchangerate/blob/master/config.php)

```php
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
```

## Tests

```
$ ./vendor/bin/phpunit tests
PHPUnit 8.5.4 by Sebastian Bergmann and contributors.

..........                                                        10 / 10 (100%)

Time: 80 ms, Memory: 4.00 MB

OK (10 tests, 16 assertions)
```
