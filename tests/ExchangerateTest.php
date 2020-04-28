<?php

namespace Tests;

use Exception;
use PHPUnit\Framework\TestCase;

class ExchangerateTest extends TestCase
{
    public function testApp()
    {
        require __DIR__ . '/../vendor/autoload.php';
        require __DIR__ . '/ExchangeRateMock.php';

        $config = json_decode('{
            "base_currency": "EUR",
            "base_rate": 1,
            "bin": {
                "url": "https://lookup.binlist.net"
            },
            "rate": {
                "url": "https://api.exchangeratesapi.io/latest"
            }
        }');

        $app = new ExchangeRateMock($config);

        $this->assertNotNull($app->config);
        $this->assertTrue(is_object($app->config));
        $this->assertTrue(method_exists($app, 'getRates'));
        $this->assertTrue(method_exists($app, 'getBin'));

        return $app;
    }

    /**
     * Invalid Config
     */
    public function testInvalidConfig()
    {
        $config = json_decode('{
            "base_currency": "EUR",
        }');

        $app = new ExchangeRateMock($config);

        $this->assertNull($app->config);
    }

    /**
     * @depends testApp
     */
    public function testGetRates($app)
    {
        $rates = $app->getRates();

        $this->assertSame('EUR', $rates->base);
        $this->assertSame(1.5226, $rates->rates->CAD);
    }

    /**
     * @depends testApp
     */
    public function testValidBin($app)
    {
        $binInfo = $app->getBin(45717360);

        $this->assertSame('mastercard', $binInfo->scheme);
        $this->assertSame('LT', $binInfo->country->alpha2);
    }

    /**
     * @depends testApp
     */
    public function testEmptyBinInfo($app)
    {
        $binInfo = $app->getBin(123456);

        $this->assertTrue(empty($binInfo->scheme));
        $this->assertTrue(empty($binInfo->country->alpha2));
    }

    /**
     * @depends testApp
     */
    public function testValidTxn($app)
    {
        $txn = json_decode('{"bin":"4745030","amount":"2000.00","currency":"GBP"}');
        $this->assertSame(45.88, $app->process($txn));
        return $txn;
    }

    /**
     * @depends testApp
     */
    public function testTxnNoBin($app)
    {
        $txn = json_decode('{"amount":"100.00","currency":"GBP"}');
        try {
            $app->process($txn);
        } catch (Exception $e) {
            $this->assertStringContainsString('$bin', $e->getMessage());
        }
    }

    /**
     * @depends testApp
     */
    public function testTxnNoAmount($app)
    {
        $txn = json_decode('{"bin":"4745030","currency":"GBP"}');
        try {
            $app->process($txn);
        } catch (Exception $e) {
            $this->assertStringContainsString('$amount', $e->getMessage());
        }
    }

    /**
     * @depends testApp
     */
    public function testTxnNoCurrency($app)
    {
        $txn = json_decode('{"bin":"4745030","amount":"100.00"}');
        try {
            $app->process($txn);
        } catch (Exception $e) {
            $this->assertStringContainsString('$currency', $e->getMessage());
        }
    }

    /**
     * @depends testApp
     * @depends testValidTxn
     */
    public function testEmptyRates($app, $txn)
    {
        $app::$rates = [];
        $this->assertSame(false, $app->process($txn));
    }
}
