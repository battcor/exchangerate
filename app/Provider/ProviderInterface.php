<?php

namespace App\Provider;

interface ProviderInterface
{
    public function __construct($api);
    public function connect();
}
