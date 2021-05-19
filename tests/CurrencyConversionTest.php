<?php

namespace GabrielAndy\Coindesk\Tests;

use Coindesk;
use GabrielAndy\Coindesk\Exceptions\CoindeskException;
use GabrielAndy\Coindesk\Exceptions\HttpException;
use GabrielAndy\Coindesk\Exceptions\UnsupportedCurrencyCode;
use GabrielAndy\Coindesk\Tests\TestCase;

class CurrencyConversionTest extends TestCase
{
    /**
     * @test
     */
    public function it_throws_exception_for_api_network_calls_error()
    {
        $this->expectException(HttpException::class);

        Coindesk::setEndpoint('https://api.google.com/jsonp/404');

        Coindesk::toBtc(1, 'USD');
    }

    /**
     * @test
     */
    public function it_will_convert_bitcoin_to_any_supported_coindesk_fiat_currency()
    {
        $this->assertIsNumeric(Coindesk::toFiatCurrency('USD', 500));
    }

    /**
     * @test
     */
    public function it_will_convert_supported_coindesk_fiat_currency_to_bitocin()
    {
        $this->assertIsNumeric(Coindesk::toBtc(1, 'USD'));
    }

    /**
     * @test
     */
    public function it_throws_exception_for_unsupported_currency()
    {
        $this->expectException(UnsupportedCurrencyCode::class);

        Coindesk::toBtc(1, 'LED');
    }

    /**
     * @test
     */
    public function it_throws_exception_on_non_numerical_value_for_btc_conversion()
    {
        $this->expectException(CoindeskException::class);

        Coindesk::toBtc('1a', 'USD');
    }

    /**
     * @test
     */
    public function it_throws_exception_when_non_numerical_value_is_passed_as_amount()
    {
        $this->expectException(CoindeskException::class);

        Coindesk::toFiatCurrency('USD', '1091a');
    }
}
