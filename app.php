<?php

/**
 * ExchangeRate
 *
 * PHP version 7.4
 *
 * @author Chris Buenafe <christopherbuenafe@gmail.com>
 */
require __DIR__ . '/vendor/autoload.php';

$data = file($argv[1]);

$config = new App\Config(__DIR__ . '/config.php');

// log output into a file
// $output = new \App\Output\File(__DIR__ . '/output.txt');

$exchange = new App\ExchangeRate($config);

foreach ($data as $line) {
    $record = json_decode($line, true);
    if (empty($record)) {
        $exchange->output->format('Record is invalid.');
        continue;
    }
    $exchange->process($record);
}
