<?php

namespace App\Provider\Rate;

use App\Provider\Provider;
use App\Provider\ProviderInterface;

class ExchangeRatesApiIo extends Provider implements ProviderInterface
{
    public function __construct($api)
    {
        parent::setApi($api);
    }

    public function connect()
    {
        return parent::consume();
    }
}
