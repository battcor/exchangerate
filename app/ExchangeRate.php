<?php

/**
 * ExchangeRate
 *
 * PHP version 7.4
 *
 * @author Chris Buenafe <christopherbuenafe@gmail.com>
 */

namespace App;

use App\Config;
use App\Output\Errorlog;
use App\Output\OutputInterface;
use App\Parser\Parser;
use Exception;

/**
 * This class calculates the commision of a transaction.
 * Retrieves BIN information and exchange rates from providers.
 */
class ExchangeRate
{
    public static $rates = null;

    /**
     * @var \App\Config
     */
    public $config;

    /**
     * @var \App\Parser\Parser
     */
    public $parser;

    /**
     * @var \App\Output\OutputInterface
     */
    public $output;

    /**
     * Construct
     *
     * @param mixed $config Configuration object
     */
    public function __construct(Config $config, OutputInterface $output = null)
    {
        $this->config = $config;
        $this->parser = new Parser();
        $this->output = $output;

        if ($output === null) {
            $this->output = new Errorlog();
        }
    }

    /**
     * Calculates commission of a transaction
     *
     * @param array $txn Array of amount, bin, and currency
     *
     * @return mixed Returns rounded up value. False if error occurs.
     */
    public function process($txn)
    {
        $xchange = $this->getRates();

        // stop process if there are no exchange rates
        if (empty($xchange)) {
            throw new Exception('Exchange rates is empty.');
        }

        $bin = $txn['bin'];
        $amount = $txn['amount'];
        $currency = $txn['currency'];

        $rate = !empty($xchange->rates->$currency) ?
            $xchange->rates->$currency : $this->config->get('base_rate');

        if ($currency === $this->config->get('base_currency') || $rate === 0) {
            $amntFixed = $amount;
        }

        if ($currency !== $this->config->get('base_currency') || $rate > 0) {
            $amntFixed = $amount / $rate;
        }

        // get BIN information
        $binInfo = $this->getBin($bin);

        $countryCode = !empty($binInfo->country->alpha2) ?
            $binInfo->country->alpha2 : null;

        // different factor for EU and Non-EU countries
        $factor = Utils::isEu($countryCode) ? 0.01 : 0.02;

        // get ceil value
        $ceil = Utils::ceil($amntFixed * $factor);

        return $this->output->format($ceil);
    }

    /**
     * Retrieves exchange rates from the provider.
     *
     * @return mixed
     */
    public function getRates()
    {
        if (self::$rates === null) {
            $api = $this->config->get('rate');
            self::$rates = $this->callProvider($api);
        }
        return self::$rates;
    }

    /**
     * Retrieves BIN information from the provider.
     *
     * @param int $bin First digits of credit card
     *
     * @return mixed
     */
    public function getBin($bin)
    {
        $api = $this->config->get('bin');
        $api['url'] = $api['url'] . '/' . $bin;

        return $this->callProvider($api);
    }

    /**
     * Instantiate API provider class
     *
     * @param array $api
     *
     * @return mixed Either one of \App\Parser:$supportedParsers
     */
    public function callProvider($api)
    {
        $provider = new $api['provider']($api);
        $provider->connect();

        // @todo Improve
        $type = explode('/', explode(';', $provider->contentType)[0])[1];

        return $this->parser->parse($provider->response, $type);
    }
}
