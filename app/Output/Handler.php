<?php

namespace App\Output;

class Handler implements OutputInterface
{
    /**
     * Returns message back as is
     *
     * @param mixed $record
     *
     *  mixed
     */
    public function format($record)
    {
        return $record;
    }
}
