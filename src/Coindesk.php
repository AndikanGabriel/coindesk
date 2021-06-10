<?php

namespace GabrielAndy\Coindesk;

use GabrielAndy\Coindesk\Exceptions\CoindeskException;
use GabrielAndy\Coindesk\Exceptions\HttpException;
use GabrielAndy\Coindesk\Exceptions\UnsupportedCurrencyCode;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Str;

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
     * Supported currencies in which conversion
     * takes place per minute.
     *
     * @var array
     */
    protected $bpiCurrencies = [
        'USD',
        'GBP',
        'EUR',
    ];

    /**
     *
     * @var bool
     */
    protected $bpiCurrencyMode = false;

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
        $this->currency = Str::upper($currencyCode);

        if (in_array($this->currency, $this->bpiCurrencies)) {
            $this->bpiCurrencyMode = true;
        } else {
            if (! in_array($this->currency, $this->supportedCurrencies())) {
                throw new UnsupportedCurrencyCode(
                    "The currency, '{$currencyCode}' is not supported by Coindesk."
                );
            }
        }

        return $this->getExchangeRates()[$this->currency];
    }

    /**
     * Get currencies supported by Coindesk.
     *
     * @return array
     *
     * @throws \GabrielAndy\Coindesk\Exceptions\HttpException
     */
    protected function supportedCurrencies(): array
    {
        try {
            $response = $this->client
                             ->request('GET', config('coindesk.supported_currency_endpoint'))
                             ->getBody();

            $supportedCurrencies = json_decode($response, true);

            foreach ($supportedCurrencies as $currency) {
                $currencies[] = $currency['currency'];
            }

            return $currencies;
        } catch (GuzzleException $e) {
            throw new HttpException($e->getMessage());
        }
    }

    /**
     * Get Bitcoin exchange rates.
     *
     * @return array
     */
    protected function getExchangeRates(): array
    {
        if (empty($this->exchangeRates)) {
            $this->setExchangeRates($this->retrieveExchangeRates());
        }

        return $this->exchangeRates;
    }

    /**
     * Set exchange rates.
     *
     * @param  array  $exchangeRates
     * @return void
     */
    protected function setExchangeRates(array $exchangeRates)
    {
        $this->exchangeRates = $exchangeRates;
    }

    /**
     * Retrieve exchange rates.
     *
     * @return array
     */
    protected function retrieveExchangeRates(): array
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
     * @throws \GabrielAndy\Coindesk\Exceptions\HttpException
     */
    protected function fetchExchangeRates(): string
    {
        try {
            $response = ($this->bpiCurrencyMode == true)
                ? $this->client->request('GET', "$this->endpoint.json")
                : $this->client->request('GET', "$this->endpoint/$this->currency.json");

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
     * @return float|string
     *
     * @throws \GabrielAndy\Coindesk\Exceptions\CoindeskException
     */
    public function toFiatCurrency($currencyCode, $btcAmount): float|string
    {
        if (! is_numeric($btcAmount)) {
            throw new CoindeskException("
                Amount should be numeric, '{$btcAmount}' given."
            );
        }

        $rate = $this->retrieveRate($currencyCode);

        return $this->computeCurrencyValue($btcAmount, $rate);
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

        return $this->formatInteger($btcAmount * $rate);
    }

    /**
     * Convert currency amount to Bitcoin.
     *
     * @param  float  $amount
     * @param  string  $currency
     * @return string
     */
    public function toBtc($amount, $currencyCode): string
    {
        if (! is_numeric($amount)) {
            throw new CoindeskException("
                Amount should be numeric, '{$amount}' given."
            );
        }

        $rate = $this->retrieveRate($currencyCode);

        return $this->computeBtcValue($amount, $rate);
    }

    /**
     * Compute Bitcoin value.
     *
     * @param  float  $amount
     * @param  float  $rate
     * @return string
     *
     * @throws \GabrielAndy\Coindesk\Exceptions\CoindeskException
     */
    public function computeBtcValue($amount, $rate)
    {
        $rate = is_numeric($rate) ? $rate : (float) $rate;

        return $this->formatInteger($amount / $rate);
    }

    /**
     * Format integer for monetal use while preserving insignificant values.
     *
     * @param  float  $integer
     * @return string
     */
    public function formatInteger(float $integer): string
    {
        // Get the float value of the integer
        $amount = strval(floatval($integer));

        // Split the amount to get the exponent
        $amount = explode('E', $amount);

        if (isset($amount[1])) {
            $exponent = $amount[1];

            $pos = strpos($exponent, '-', 0);

            $exponent = abs($exponent);

            if ($pos !== false) {
                $digit = round($amount[0], 1);

                $digit = bcdiv($digit, 10**$exponent, $exponent+1);
            } else {
                $digit = bcmul($amount[0], 10**$exponent, 2);
            }
        } else {
            $digit = number_format($integer, 2, '.', '');
        }

        return $digit;
    }
}
