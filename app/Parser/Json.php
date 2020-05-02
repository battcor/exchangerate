<?php

namespace App\Parser;

class Json implements ParserInterface
{
    /**
     * Returns decoded JSON string
     *
     * @param string $data
     *
     * @return object
     */
    public function decode($data)
    {
        return json_decode($data);
    }
}
