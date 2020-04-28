<?php

namespace Tests;

class ExchangeRateMock extends \App\ExchangeRate
{
    public static $rates = null;

    public function getRates()
    {
        if (self::$rates === null) {
            $rates = json_decode('{
                "rates": {
                    "CAD": 1.5226,
                    "HKD": 8.3489,
                    "ISK": 158.6,
                    "PHP": 54.571,
                    "DKK": 7.4577,
                    "HUF": 357.51,
                    "CZK": 27.551,
                    "AUD": 1.6918,
                    "RON": 4.8425,
                    "SEK": 10.8883,
                    "IDR": 16772,
                    "INR": 81.94,
                    "BRL": 5.8666,
                    "RUB": 80.6062,
                    "HRK": 7.575,
                    "JPY": 115.75,
                    "THB": 34.815,
                    "CHF": 1.0511,
                    "SGD": 1.5331,
                    "PLN": 4.5379,
                    "BGN": 1.9558,
                    "TRY": 7.4788,
                    "CNY": 7.6259,
                    "NOK": 11.5165,
                    "NZD": 1.7947,
                    "ZAR": 20.5432,
                    "USD": 1.0772,
                    "MXN": 26.3418,
                    "ILS": 3.8207,
                    "GBP": 0.872,
                    "KRW": 1326.8,
                    "MYR": 4.6966
                },
                "base": "EUR",
                "date": "2020-04-23"
            }');
            self::$rates = $rates;
        }
        return self::$rates;
    }

    public function getBin($bin)
    {
        $binInfo = json_decode('{
            "45717360": {
                "number": {},
                "scheme": "mastercard",
                "type": "debit",
                "brand": "Debit",
                "country": {
                    "numeric": "440",
                    "alpha2": "LT",
                    "name": "Lithuania",
                    "emoji": "🇱🇹",
                    "currency": "EUR",
                    "latitude": 56,
                    "longitude": 24
                },
                "bank": {}
            }
        }');

        return !empty($binInfo->$bin) ? $binInfo->$bin : null;
    }
}
