# CoinDesk Bitcoin Price Index API for Laravel

> Implement CoinDesk Bitcoin Price Index (BPI) on Laravel apps

This package allows you to query for the Bitcoin exchange rates in supported [CoinDesk's](https://www.coindesk.com) fiat currencies in [Laravel](https://laravel.com) applications.

## Requirements

- PHP >= 7.4
- Laravel >= 7.0
- Guzzle 7.0

## Installation

You can install the package via composer:

``` bash
composer require gabrielandy/coindesk
```

## Usage

You can get the current price of Bitcoin in any currency supported by Coindesk.
Currently, Coindesk supports USD, GBP and EUR.

### To get the price of Bitcoin in USD

``` php
use Coindesk;

/**
 * Convert from Bitcoin to Coindesk's supported fiat currency (USD, GBP, EUR).
 *
 * @example Coindesk::toFiatCurrency('USD', 1);
 *
 * @param  string  $currency_code  - The ISO 4217 fiat currency you wish to convert Bitcoin to
 * @param  int  $bitcoin_amount = 1 - The value of Bitcoin in float/numeric
 * @return float
 */
Coindesk::toFiatCurrency($currency_code, $bitcoin_amount);

Coindesk::toFiatCurrency('USD', 1); // This will return `57553.52` stating that BTC 1 = $57,553.52
Coindesk::toFiatCurrency('EUR', 1); // This will return `47321.11` stating that BTC 1 = €47,321.11
```

### To convert any Coindesk's supported currency to Bitcoin
```php
use Coindesk;

/**
 * Convert any supported Coindesk's currency to Bitcoin.
 *
 * @example Coindesk::toBtc(1, 'USD');
 * @param int $amount = 1 : The amount of the currency in integer/numeric
 * @param string $currency = USD : The currency you wish to convert to Bitcoin
 * @return float
 */
Coindesk::toBtc($amount, $currency_code);

Coindesk::toBtc(1, 'USD'); // This will return '0.000017; stating that $1 = BTC 0.000017
```

You can get the price of Bitcoin in any local currency other than the supported ones (USD, GBP and EUR)

```php
use Coindesk;

// Get current Bitcoin price in USD
$bitcoinUSDPrice = Coindesk::toCurrency('USD', 1); // $57,250.03

// Set exchange rate of local currency to USD
$exchangeRate = 440  // usign $1 = ₦440

// Compute conversion
$computed = $bitcoinUSDPrice * $exchangeRate; // 57250.03 * 440

// Computed result returns float
echo $computed;  // N25,190,013.20

```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please visit the [Contributing](CONTRIBUTING.md) page for details.

## Security
If you discover any security related issues, please email master@andikangabriel.com instead of using the issue tracker.

## License

The MIT License. Please see [License](LICENSE.md) file for details.

## Disclaimer

This project is not affiliated in any way with CoinDesk. It is intended to provide a useful service and comes with no warranty or any kind. The author is not responsible for any damages or problems incurred during usage of the API.

You are free to use this package to consume Coindesk's API as you see fit, as long as each page or app that uses it includes the text "Powered by [CoinDesk](https://www.coindesk.com/price/bitcoin)", linking to Coindesk's [pricing page](https://www.coindesk.com/price/bitcoin).
