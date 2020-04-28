<?php
/**
 * ExchangeRate
 *
 * PHP version 7.4
 *
 * @author Chris Buenafe <christopherbuenafe@gmail.com>
 */

namespace App;

/**
 * This class calculates the commision of a transaction.
 * Retrieves BIN information and exchange rates from providers.
 */
class ExchangeRate
{
    public static $rates = null;

    public $config = null;

    /**
     * Construct
     *
     * @param mixed $config Configuration object
     */
    public function __construct($config)
    {
        $this->config = is_array($config) ?
            json_decode(json_encode($config)) : $config;
    }

    /**
     * Calculates commission of a transaction
     *
     * @param object $txn Object with amount, bin, currency properties
     *
     * @return mixed Returns rounded up value. False if error occurs.
     */
    public function process($txn)
    {
        $xchange = $this->getRates();

        // stop process if there are no exchange rates
        if (empty($xchange)) {
            // show/log error message here
            return false;
        }

        $amount = $txn->amount;
        $currency = $txn->currency;

        $rate = !empty($xchange->rates->$currency) ?
            $xchange->rates->$currency : $this->config->base_rate;

        if ($currency === $this->config->base_currency || $rate === 0) {
            $amntFixed = $amount;
        }

        if ($currency !== $this->config->base_currency || $rate > 0) {
            $amntFixed = $amount / $rate;
        }

        // get BIN information
        $binInfo = $this->getBin($txn->bin);

        $countryCode = !empty($binInfo->country->alpha2) ?
            $binInfo->country->alpha2 : null;

        // different factor for EU and Non-EU countries
        $factor = $this->isEu($countryCode) ? 0.01 : 0.02;

        return ceil($amntFixed * $factor * 100) / 100;
    }

    /**
     * Retrieves exchange rates from the provider.
     *
     * @return mixed
     */
    public function getRates()
    {
        if (self::$rates === null) {
            $api = $this->config->rate;
            self::$rates = $this->callApi($api);
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
        $api = $this->config->bin;
        $api->url = $api->url . '/' . $bin;
        return $this->callApi($api);
    }

    /**
     * Consumes provider's API
     *
     * @param object $api API configuration object
     *
     * @return mixed
     */
    protected function callApi($api)
    {
        $ch = curl_init();

        $opt = [
            CURLOPT_URL => $api->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_CUSTOMREQUEST => !empty($api->method) ? $api->method : 'GET'
        ];

        if (!empty($api->user) && !empty($api->password)) {
            $opt[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
            $opt[CURLOPT_USERPWD] = $api->user . ':' . $api->password;
        }

        if (!empty($api->fields)) {
            $fields = is_array($api->fields) ?
                http_build_query($api->fields) : $api->fields;
            $opt[CURLOPT_POSTFIELDS] = $fields;
        }

        curl_setopt_array($ch, $opt);

        $response = curl_exec($ch);
        $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);
        return $this->parseResponse($response, $contentType);
    }

    /**
     * Parses string into an object
     *
     * @param string $response    String in json or xml format
     * @param string $contentType API content-type
     *
     * @return mixed
     */
    protected function parseResponse($response, $contentType)
    {
        if (strstr($contentType, 'json')) {
            return json_decode($response);
        }

        if (strstr($contentType, 'xml')) {
            return simplexml_load_string($response);
        }
        return null;
    }

    /**
     * Checks if country code belongs to EU
     *
     * @param string $code Country code
     *
     * @return boolean
     */
    protected function isEu($code)
    {
        $countries = [
            'AT', 'BE', 'BG', 'CY', 'CZ', 'DE', 'DK', 'EE', 'ES', 'FI', 'FR', 'GR',
            'HR', 'HU', 'IE', 'IT', 'LT', 'LU', 'LV', 'MT', 'NL', 'PO', 'PT', 'RO',
            'SE', 'SI', 'SK'
        ];
        return in_array($code, $countries);
    }
}

