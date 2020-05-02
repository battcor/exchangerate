<?php

namespace App\Parser;

use Exception;

class Parser
{
    public $supportedParsers = [
        'json' => 'App\Parser\Json',
        'xml' => 'App\Parser\Xml'
    ];

    /**
     * Instantiates parser class based on $type
     *
     * @param string $data
     * @param string $type
     *
     * @return mixed
     */
    public function parse($data, $type)
    {
        if (!array_key_exists($type, $this->supportedParsers)) {
            throw new Exception('Content type is not supported.');
        }
        return (new $this->supportedParsers[$type])->decode($data);
    }
}
