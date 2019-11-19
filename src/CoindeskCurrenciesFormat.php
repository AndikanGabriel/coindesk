<?php

namespace GabrielAndy\Coindesk;

class CoindeskCurrenciesFormat
{
    /**
     * List of all crypto currencies.
     *
     * @return array
     */
	public static function cryptoCurrencies(): array
	{
		return [
	        'AUR', 'BIS', 'BTC', 'XBT', 'BCH', 'BCC', 'BC', 'BURST', 'DASH', 'DOGE', 'XDG', 'XDN', 'EMC', 'ETH',
	        'ETC', 'GRC', 'IOT', 'MIOTA', 'LTC', 'MZC', 'XMR', 'NMC', 'XEM', 'NXT', 'MSC', 'PPC', 'POT', 'XPM',
	        'XRP', 'SIL', 'STC', 'AMP', 'TIT', 'UBQ', 'VTC', 'ZEC', 'XBC', 'XLM', 'XZC', 'NEO', 'LSK', 'STRAT',
	        'WAVES', 'BCN', 'HSR', 'BTS', 'STEEM', 'KMD', 'ARK', 'FCT', 'SC', 'GBYTE', 'PIVX', 'DCR', 'DGB',
	        'NXS', 'BTCD', 'GAME', 'SYS', 'BLOCK', 'XVG', 'NAV', 'LKK', 'UBQ', 'PART', 'NLC2', 'GXS', 'NLG', 'DCT',
	        'FRST', 'RISE', 'EMC', 'LEO', 'XEL', 'IOC', 'XAS', 'ADK', 'PPC', 'RDD', 'WTC', 'FAIR', 'VTC', 'XCP',
	        'VIA', 'ETP', 'MONA', 'EXP', 'CLOACK', 'OK', 'ION', 'SIB', 'TCC', 'EB3', 'LBC', 'RADS', 'BAY', 'CRW',
	        'POT', 'CLAM', 'PPY', 'SKY', 'ZEN', 'UNO', 'MUE', 'SHIFT', 'BLK', 'SPR', 'SLS', 'GOLOS', 'OMNI', 'YBC',
	        'ENRG', 'MOON', 'RBY', 'VRC', 'XRB', 'ECN', 'DMD', 'EDR'
		];
	}
}
