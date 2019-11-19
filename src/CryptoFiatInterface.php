<?php

namespace GabrielAndy\Coindesk;

Interface CryptoFiatInterface
{
    /**
     * Get rate of currency.
     *
     * @param  string $currencyCode
     * @return float
     */
	public function retrieveRate($currencyCode);

    /**
     * Convert Bitcoin amount to a specific currency.
     *
     * @param  string $currencyCode
     * @param  float  $btcAmount
     * @return float
     */
	public function toCurrency($currencyCode, $btcAmount);

    /**
     * Compute currency value.
     *
     * @param  float $btcAmount
     * @param  float $rate
     * @return float
     * @throws Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException
     */
    public function computeCurrencyValue($btcAmount, $rate);


    /**
     * Format value based on currency.
     *
     * @param  string $currencyCode
     * @param  float  $value
     * @return float
     */
    public function formatToCurrency($currencyCode, $value);


    /**
     * Convert currency amount to Bitcoin.
     *
     * @param  float  $amount
     * @param  string $currency
     * @return float
     */
    public function toBtc($amount, $currencyCode);

    /**
     * Compute Bitcoin value.
     *
     * @param  float $amount
     * @param  float $rate
     * @return float
     * @throws Jimmerioles\BitcoinCurrencyConverter\Exception\InvalidArgumentException
     */
    public function computeBtcValue($amount, $rate);
}