<?php

namespace App\Output;

use Exception;

class File implements OutputInterface
{
    /**
     * @var string
     */
    protected $file;

    /**
     * @var resource
     */
    protected $stream;

    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Writes message to a file
     *
     * @param mixed $record
     *
     * @return void
     */
    public function format($record)
    {
        $stream = fopen($this->file, 'a');

        if (!is_resource($stream)) {
            throw new Exception('File is invalid.');
        }

        fwrite($stream, $record . PHP_EOL);
    }
}
