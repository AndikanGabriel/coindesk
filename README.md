# CoinDesk Bitcoin Price Index API for Laravel

> Implement CoinDesk Bitcoin Price Index (BPI) on Laravel apps

This package allows you to query for the Bitcoin exchange rates in supported [CoinDesk's](https://www.coindesk.com) fiat [currencies](https://api.coindesk.com/v1/bpi/supported-currencies.json) in [Laravel](https://laravel.com) applications.

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

### To get the price of a Bitcoin value in any supported fiat currency
``` php
use Coindesk;

/**
 * Convert from Bitcoin to Coindesk's supported fiat currency (USD, GBP, EUR, NGN, GHC).
 *
 * @example Coindesk::toFiatCurrency('USD', 1)
 *
 * @param  string  $currency_code  - The ISO 4217 fiat currency you wish to convert Bitcoin to
 * @param  int  $bitcoin_amount    - The value of Bitcoin in float/numeric 
 * @return float
 */
Coindesk::toFiatCurrency($currency_code, $bitcoin_amount);

Coindesk::toFiatCurrency('USD', 1); // This will return 36579.71 stating that ₿1 = $36,579.71
Coindesk::toFiatCurrency('EUR', 1); // This will return 29951.01 stating that ₿1 = €29,951.01
Coindesk::toFiatCurrency('NGN', 1); // This will return 15028918.04 stating that ₿1 = ₦15,028,918.04
```

### To convert any Coindesk's supported fiat currency value to Bitcoin
```php
use Coindesk;

/**
 * Convert any supported Coindesk's fiat currency to Bitcoin.
 *
 * @example Coindesk::toBtc(1, 'USD')
 *
 * @param  int  $amount             - The amount of the currency in integer/numeric
 * @param  string  $currency = USD  - The currency you wish to convert to Bitcoin
 * @return string
 */
Coindesk::toBtc($amount, $currency_code);

Coindesk::toBtc(1, 'USD'); // This will return 0.000027 stating that $1 = ₿0.000027
Coindesk::toBtc(1, 'EUR'); // This will return 0.000034 stating that €1 = ₿0.000034
Coindesk::toBtc(1, 'NGN'); // This will return 0.000000067 stating that ₦1 = ₿0.000000067
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
