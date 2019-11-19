# CoinDesk for Laravel

> Implement CoinDesk Bitcoin Price Index with Laravel

This package allows you to use [CoinDesk](https://www.coindesk.com) in [Laravel 5+](https://laravel.com). This version of CoinDesk is inspired from [Jim Merioles' Bitcoin currency converter php-package](https://github.com/jimmerioles/bitcoin-currency-converter-php).

## Requirements

- PHP >= 7.2
- Laravel >= 5.0
- Guzzle 6.0

## Installation

1. Install the package via composer:

``` bash
composer require gabrielandy/coindesk
```

2. Register the Coindesk service provider: If you are running Laravel 5.5+ skip this step, because, the package will be auto-discovered

```php
// config/app.php
'providers' => [
    ...
    GabrielAndy\Coindesk\CoindeskServiceProvider::class,
];

...

'aliases' => [
	...
	'Coindesk' => GabrielAndy\Coindesk\Facades\Coindesk::class,
]
```

## Configuration
Coindex for Laravel comes with little configuration to be made by the user, if needed. Versions 1.x only supports the API version 1 of CoinDesk. You are however free to use higher endpoint versions. To use higher endpoint version, publish the configuration file and change the defualt endpoint:

```bash
php artisan vendor:publish --provider="GabrielAndy\Coindesk\CoindeskServiceProvider" --tag="config"
```


## Usage

You can get the current price of Bitcoin in any currency supported by Coindesk.
Currently, Coindesk supports USD, GBP and EUR.

To get the price of Bitcoin in USD

``` php
use Coindesk;

/**
 * Convert from Bitcoin to any currency (ISO 4217 fiat or crypto).
 *
 * @example Coindesk::toCurrency(1, 'USD');
 * @param string $currency = USD : The currency you wish to convert Bitcoin to
 * @param int $amount = 1 : The amount of the currency in integer/numeric
 * @return float
 */
Coindesk::toCurrency($currency_code, $bitcoin_amount);
```

To convert any currency (ISO 4217 fiat or crypto) to Bitcoin:
```php
use Coindesk;

/**
 * Convert any currency (ISO 4217 fiat or crypto) to Bitcoin.
 *
 * @example Coindesk::toBtc(1, 'USD');
 * @param int $amount = 1 : The amount of the currency in integer/numeric
 * @param string $currency = USD : The currency you wish to convert to Bitcoin
 * @return float
 */
Coindesk::toBtc($amount, $currency_code);
```

You can get the price of Bitcoin in any local currency other than the supported ones (USD, GBP and EUR)

```php
use Coindesk;

// Get current Bitcoin price in USD
$bitcoinUSDPrice = Coindesk::toCurrency('USD', 1);

// Set exchange rate of local currency to USD
$exchangeRate = 360  // assuming $1 = ₦360

// Compute conversion
$computed = $bitcoinUSDPrice * $exchangeRate;

// Computed result returns float
return $computed;  // 2918975.4 

```

## Contribute

Contributions are very welcome! Send a pull request to the version's branch or report any issues you find on the issue tracker.

## License

Coindesk for Laravel is released under the MIT License. See the bundled [LICENSE](LICENSE.md) file for details.

## Disclaimer

This project is not affiliated in any way with CoinDesk. It is intended to provide a useful service and comes with no warranty or any kind. The author is not responsible for any damages or problems incurred during usage of the API.
