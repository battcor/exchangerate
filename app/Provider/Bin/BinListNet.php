<?php

namespace App\Provider\Bin;

use App\Provider\Provider;
use App\Provider\ProviderInterface;

class BinListNet extends Provider implements ProviderInterface
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
