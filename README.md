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

```php
<?php

require __DIR__ . '/vendor/autoload.php';

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
```

## Tests

```
$ ./vendor/bin/phpunit tests
PHPUnit 8.5.4 by Sebastian Bergmann and contributors.

..........                                                        10 / 10 (100%)

Time: 80 ms, Memory: 4.00 MB

OK (10 tests, 16 assertions)
```
