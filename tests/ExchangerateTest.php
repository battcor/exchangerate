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

        $config = new \App\Config(__DIR__ . '/../config.php');
        $app = new ExchangeRateMock($config, new \App\Output\Handler());

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
        $invalid = false;
        try {
            $config = new \App\Config(__DIR__ . '/config-invalid.php');
        } catch (Exception $e) {
            $invalid = true;
        }

        $this->assertTrue($invalid);
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
        $txn = json_decode('{"bin":"4745030","amount":"2000.00","currency":"GBP"}', true);
        $this->assertSame(45.88, $app->process($txn));
        return $txn;
    }

    /**
     * @depends testApp
     */
    public function testTxnNoBin($app)
    {
        $txn = json_decode('{"amount":"100.00","currency":"GBP"}', true);
        try {
            $app->process($txn);
        } catch (Exception $e) {
            $this->assertStringContainsString('bin', $e->getMessage());
        }
    }

    /**
     * @depends testApp
     */
    public function testTxnNoAmount($app)
    {
        $txn = json_decode('{"bin":"4745030","currency":"GBP"}', true);
        try {
            $app->process($txn);
        } catch (Exception $e) {
            $this->assertStringContainsString('amount', $e->getMessage());
        }
    }

    /**
     * @depends testApp
     */
    public function testTxnNoCurrency($app)
    {
        $txn = json_decode('{"bin":"4745030","amount":"100.00"}', true);
        try {
            $app->process($txn);
        } catch (Exception $e) {
            $this->assertStringContainsString('currency', $e->getMessage());
        }
    }

    /**
     * @depends testApp
     * @depends testValidTxn
     */
    public function testEmptyRates($app, $txn)
    {
        $app::$rates = [];
        $invalid = false;

        try {
            $app->process($txn);
        } catch (Exception $e) {
            $invalid = true;
        }

        $this->assertTrue($invalid);
    }
}
