<?php

namespace App\Config\Parser;

use Exception;

class Php implements ParserInterface
{
    /**
     * Validates if file exist.
     *
     * @param string $filename
     *
     * @return array
     */
    public function parseFile($filename)
    {
        if (!file_exists($filename)) {
            throw new Exception('File does not exist ' . $filename);
        }

        $data = require $filename;

        return (array) $this->parse($data);
    }

    /**
     * Validates if callable and has an array
     *
     * @param callable|mixed $data
     *
     * @return array
     */
    protected function parse($data)
    {
        if (is_callable($data)) {
            $data = call_user_func($data);
        }

        if (!is_array($data)) {
            throw new Exception('Only array is supported.');
        }

        return $data;
    }
}
