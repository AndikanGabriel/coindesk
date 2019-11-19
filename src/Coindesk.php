<?php

namespace GabrielAndy\Coindesk;

use GuzzleHttp\Client;
use GabrielAndy\Coindesk\Exceptions\ErrorsException;

class Coindesk implements CryptoFiatInterface
{
    /** @var \GuzzleHttp\Client */
    protected $client;

    /** @var string */
    protected $apiEndpoint;


    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function setAPIUrl(string $apiEndpoint)
    {
    	$this->apiEndpoint = $apiEndpoint;

    	return $this;
    }

    /**
     * Get the rate of a currency to Bitcoin.
     *
     * @param  string $currencyCode
     * @return float
     */
	public function retrieveRate($currencyCode)
	{
        if (! Helper::isCurrencyCode($currencyCode)) {
            throw ErrorsException::customError("Argument passed is not a valid currency code, '{$currencyCode}' given.");
        }

        $exchangeRates = $this->getExchangeRates();

        if (! Helper::currencySupport($currencyCode, $this->exchangeRates)) {
            throw ErrorsException::customError("{$currencyCode} currency code is not supported by Coindesk.");
        }

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
     * @param array $exchangeRatesArray
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
        $exchangeRatesArray = $this->parseToExchangeRatesArray($this->fetchExchangeRates());

        return $exchangeRatesArray;
    }

    /**
     * Fetch exchange rates json data from API endpoint.
     *
     * @return string|json
     */
    protected function fetchExchangeRates()
    {
        $response = $this->client->request('GET', $this->apiEndpoint);

        if ($response->getStatusCode() != 200) {
            throw ErrorsException::customError("Not OK response received from API endpoint.");
        }

        return $response->getBody();
    }

    /**
     * Parse retrieved JSON data to exchange rates associative array.
     * i.e. ['BTC' => 1, 'USD' => 4000.00, ...]
     *
     * @param  string|json $rawJsonData
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
     * Convert Bitcoin amount to a specific currency.
     *
     * @param  string $currencyCode
     * @param  float  $btcAmount
     * @return float
     */
	public function toCurrency($currencyCode, $btcAmount)
	{
        $rate = $this->retrieveRate($currencyCode);

        $value = $this->computeCurrencyValue($btcAmount, $rate);

        return $this->formatToCurrency($currencyCode, $value);
	}

    /**
     * Compute currency value.
     *
     * @param  float $btcAmount
     * @param  float $rate
     * @return float
     * @throws Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException
     */
    public function computeCurrencyValue($btcAmount, $rate)
    {
        if (! is_numeric($btcAmount)) {
            throw ErrorsException::customError("Argument \$btcAmount should be numeric, '{$btcAmount}' given.");
        }

        return $btcAmount * $rate;
    }


    /**
     * Format value based on currency.
     *
     * @param  string $currencyCode
     * @param  float  $value
     * @return float
     */
    public function formatToCurrency($currencyCode, $value)
    {
        if (Helper::isCryptoCurrency($currencyCode)) {
            return round($value, 8, PHP_ROUND_HALF_UP);
        }
        if (Helper::isFiatCurrency($currencyCode)) {
            return round($value, 2, PHP_ROUND_HALF_UP);
        }
        throw ErrorsException::customError("Argument \$currencyCode not valid currency code, '{$currencyCode}' given.");
    }


    /**
     * Convert currency amount to Bitcoin.
     *
     * @param  float  $amount
     * @param  string $currency
     * @return float
     */
    public function toBtc($amount, $currencyCode)
    {
        $rate = $this->retrieveRate($currencyCode);

        $value = $this->computeBtcValue($amount, $rate);

        return $this->formatToCurrency('BTC', $value);
    }

    /**
     * Compute Bitcoin value.
     *
     * @param  float $amount
     * @param  float $rate
     * @return float
     * @throws Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException
     */
    public function computeBtcValue($amount, $rate)
    {
        if (! is_numeric($amount)) {
            throw ErrorsException::customError("Argument \$amount should be numeric, '{$amount}' given.");
        }

        return $amount / $rate;
    }
}