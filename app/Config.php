<?php

namespace App;

use App\Config\Parser\ParserInterface;

class Config
{
    public $data = null;
    public $defaultParser = 'App\Config\Parser\Php';

    public function __construct($file, ParserInterface $parser = null)
    {
        if ($this->data === null) {
            $class = $parser === null ? $this->defaultParser : $parser;
            try {
                $parser = new $class();
            } catch (Exception $e) {
                throw $e;
            }
            $this->data = $parser->parseFile($file);
        }
        return $this;
    }

    /**
     * Retrieves config based on key
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return !empty($this->data[$key]) ? $this->data[$key] : null;
    }
}
