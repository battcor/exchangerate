<?php

namespace App\Parser;

class Xml implements ParserInterface
{
    /**
     * Returns decoded XML string
     *
     * @param string $data
     *
     * @return mixed
     */
    public function decode($data)
    {
        return simplexml_load_string($data);
    }
}
