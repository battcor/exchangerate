<?php

namespace App\Output;

class Errorlog implements OutputInterface
{
    /**
     * Sends message to PHP's system logger
     *
     * @param mixed $record
     *
     * @return void
     */
    public function format($record)
    {
        error_log($record, 0);
    }
}
