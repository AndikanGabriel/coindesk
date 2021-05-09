<?php

namespace GabrielAndy\Coindesk;

use GabrielAndy\Coindesk\Exceptions\CoindeskException;
use GabrielAndy\Coindesk\Exceptions\HttpException;
use GabrielAndy\Coindesk\Exceptions\UnsupportedCurrencyCode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class Coindesk
{
    /**
     * The GuzzleHttpClient instance.
     *
     * @var \GuzzleHttp\Client
     */
    protected $client;

    /**
     * The Coindesk API endpoint.
     *
     * @var string
     */
    protected $endpoint;

    /**
     * Coindesk's supported currencies.
     *
     * @var array
     */
    protected $supportedCurrencies = [
        'USD',
        'GBP',
        'EUR',
    ];

    /**
     * Create a new Coindesk instance.
     *
     * @param  \GuzzleHttp\Client  $client
     * @return void
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Set the Coindesk's API endpoint being used.
     *
     * @param  \GuzzleHttp\Client  $client
     * @return void
     */
    public function setEndpoint(string $endpoint)
    {
        $this->endpoint = $endpoint;

        return $this;
    }

    /**
     * Get the rate of a currency to Bitcoin.
     *
     * @param  string  $currencyCode
     * @return float
     *
     * @throws \GabrielAndy\Coindesk\Exceptions\UnsupportedCurrencyCode
     */
    public function retrieveRate($currencyCode)
    {
        if (! in_array(
            strtoupper($currencyCode), $this->supportedCurrencies
        )) {
            throw new UnsupportedCurrencyCode(
                "The currency, '{$currencyCode}' is not supported by Coindesk."
            );
        }

        $exchangeRates = $this->getExchangeRates();

        return $exchangeRates[strtoupper($currencyCode)];
    }

    /**
     * Get Bitcoin exchange rates in an associative array.
     *
     * @return array
     */
    protected function getExchangeRates()
    {
        if (empty($this->exchangeRates)) {
            $this->setExchangeRates($this->retrieveExchangeRates());
        }

        return $this->exchangeRates;
    }

    /**
     * Set exchange rates.
     *
     * @param  array  $exchangeRatesArray
     * @return void
     */
    protected function setExchangeRates($exchangeRatesArray)
    {
        $this->exchangeRates = $exchangeRatesArray;
    }

    /**
     * Retrieve exchange rates.
     *
     * @return array
     */
    protected function retrieveExchangeRates()
    {
        $exchangeRatesArray = $this->parseToExchangeRatesArray(
            $this->fetchExchangeRates()
        );

        return $exchangeRatesArray;
    }

    /**
     * Fetch exchange rates json data from API endpoint.
     *
     * @return string
     *
     * @throws \GabrielAndy\Coindesk\Exceptions\CoindeskException
     */
    protected function fetchExchangeRates()
    {
        try {
            $response = $this->client->request('GET', $this->endpoint);

            return $response->getBody();
        } catch (GuzzleException $e) {
            throw new HttpException($e->getMessage());
        }
    }

    /**
     * Parse retrieved JSON data to exchange rates associative array.
     * i.e. ['BTC' => 1, 'USD' => 4000.00, ...]
     *
     * @param  string  $rawJsonData
     * @return array
     */
    protected function parseToExchangeRatesArray($rawJsonData)
    {
        $arrayData = json_decode($rawJsonData, true);

        foreach ($arrayData['bpi'] as $value) {
            $exchangeRatesArray[$value['code']] = $value['rate_float'];
        }

        return $exchangeRatesArray;
    }

    /**
     * Convert Bitcoin amount to a Coindesk's supported fiat currency.
     *
     * @param  string  $currencyCode
     * @param  float  $btcAmount
     * @return string
     *
     * @throws \GabrielAndy\Coindesk\Exceptions\CoindeskException
     */
    public function toFiatCurrency($currencyCode, $btcAmount)
    {
        if (! is_numeric($btcAmount)) {
            throw new CoindeskException("
                Amount should be numeric, '{$btcAmount}' given."
            );
        }

        $rate = $this->retrieveRate($currencyCode);

        $value = $this->computeCurrencyValue($btcAmount, $rate);

        return number_format(
            $value,
            config('coindesk.btc_fiat_precision'),
            '.',
            ''
        );
    }

    /**
     * Compute currency value.
     *
     * @param  float  $btcAmount
     * @param  float  $rate
     * @return float
     */
    public function computeCurrencyValue($btcAmount, $rate)
    {
        $rate = is_numeric($rate) ? $rate : (float) $rate;

        return $btcAmount * $rate;
    }

    /**
     * Convert currency amount to Bitcoin.
     *
     * @param  float  $amount
     * @param  string  $currency
     * @return string
     */
    public function toBtc($amount, $currencyCode)
    {
        if (! is_numeric($amount)) {
            throw new CoindeskException("
                Amount should be numeric, '{$amount}' given."
            );
        }

        $rate = $this->retrieveRate($currencyCode);

        $value = $this->computeBtcValue($amount, $rate);

        return number_format(
            $value,
            config('coindesk.fiat_btc_precision'),
            '.',
            ''
        );
    }

    /**
     * Compute Bitcoin value.
     *
     * @param  float  $amount
     * @param  float  $rate
     * @return float
     *
     * @throws \GabrielAndy\Coindesk\Exceptions\CoindeskException
     */
    public function computeBtcValue($amount, $rate)
    {
        $rate = is_numeric($rate) ? $rate : (float) $rate;

        return $amount / $rate;
    }
}
