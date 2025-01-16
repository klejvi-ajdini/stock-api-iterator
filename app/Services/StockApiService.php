<?php

declare(strict_types=1);

namespace App\Services;

/**
 * Class StockApiService
 *
 * Provides methods to fetch stock data using different API endpoints
 * and return a StockApiIterator to traverse the results.
 */
class StockApiService
{
    /**
     * Fetches daily stock data for a specific symbol using the TIME_SERIES_DAILY endpoint.
     *
     * @param  string  $symbol  The stock symbol (e.g., 'IBM').
     * @param  array  $params  Additional parameters for the API request.
     * @return StockApiIterator An iterator over the daily stock data.
     */
    public function fetchStockData(string $symbol, array $params = []): StockApiIterator
    {
        return new StockApiIterator(array_merge($params, ['function' => 'TIME_SERIES_DAILY', 'symbol' => $symbol]));
    }

    /**
     * Fetches the quote data for a specific symbol using the GLOBAL_QUOTE endpoint.
     *
     * @param  string  $symbol  The stock symbol (e.g., 'IBM').
     * @param  array  $params  Additional parameters for the API request.
     * @return StockApiIterator An iterator over the quote data.
     */
    public function fetchQuoteData(string $symbol, array $params = []): StockApiIterator
    {
        return new StockApiIterator(array_merge($params, ['function' => 'GLOBAL_QUOTE', 'symbol' => $symbol]));
    }
}
