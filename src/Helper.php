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
}
