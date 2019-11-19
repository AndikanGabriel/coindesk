<?php

namespace GabrielAndy\Coindesk;

class Helper
{
    /**
     * Check for a crypto currency.
     *
     * @param  string  $currencyCode
     * @return boolean
     */
    public static function isCryptoCurrency($cryptoCode)
    {
        return in_array(strtoupper($cryptoCode), CoindeskCurrenciesFormat::cryptoCurrencies());
    }

    /**
     * Check for a currency code.
     *
     * @param  string  $currencyCode
     * @return boolean
     */
    public static function isCurrencyCode($currencyCode)
    {
        return self::isFiatCurrency($currencyCode) || self::isCryptoCurrency($currencyCode);
    }

    /**
     * Check for a fiat currency code.
     *
     * @param  string  $currencyCode
     * @return boolean
     */
    public static function isFiatCurrency($currencyCode)
    {
        return in_array(strtoupper($currencyCode), CoindeskCurrenciesFormat::fiatCurrencies());
    }

    /**
     * Check if currency code is supported by Coindesk.
     *
     * @param  string  $currencyCode
     * @return boolean
     */
    public static function currencySupport($currencyCode, $finder)
    {
        return in_array(strtoupper($currencyCode), array_keys($finder));
    }
}