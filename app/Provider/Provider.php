<?php

namespace App\Provider;

class Provider
{
    /**
     * @var array
     */
    public $api;

    /**
     * @var string
     */
    public $response;

    /**
     * @var string
     */
    public $contentType;

    /**
     * Setter function for \App\Provider:$api
     *
     * @param array $api
     *
     * @return void
     */
    public function setApi($api)
    {
        $this->api = $api;
    }

    /**
     * Consumes provider's API
     *
     * @param object $api API configuration
     *
     * @return mixed
     */
    public function consume()
    {
        $ch = curl_init();

        $opt = [
            CURLOPT_URL => $this->api['url'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_IPRESOLVE => CURL_IPRESOLVE_V4,
            CURLOPT_CUSTOMREQUEST => !empty($this->api['method']) ? $this->api['method'] : 'GET'
        ];

        if (!empty($this->api['user']) && !empty($this->api['password'])) {
            $opt[CURLOPT_HTTPAUTH] = CURLAUTH_BASIC;
            $opt[CURLOPT_USERPWD] = $this->api['user'] . ':' . $this->api['password'];
        }

        if (!empty($this->api['fields'])) {
            $fields = is_array($this->api['fields']) ?
                http_build_query($this->api['fields']) : $this->api['fields'];
            $opt[CURLOPT_POSTFIELDS] = $fields;
        }

        curl_setopt_array($ch, $opt);

        $this->response = curl_exec($ch);
        $this->contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);
        curl_close($ch);
    }
}
