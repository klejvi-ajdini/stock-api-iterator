<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\StockApiService;
use Illuminate\Support\Collection;
use Inertia\Inertia;

/**
 * Class StockController
 *
 * Handles requests related to stock data and renders the appropriate Inertia views.
 */
class StockController extends Controller
{
    /**
     * @var StockApiService The service class for interacting with the stock API.
     */
    protected StockApiService $stockApiService;

    /**
     * StockController constructor.
     *
     * @param  StockApiService  $stockApiService  An instance of the StockApiService.
     */
    public function __construct(StockApiService $stockApiService)
    {
        $this->stockApiService = $stockApiService;
    }

    /**
     * Displays the daily stock data for a given symbol.
     *
     * @param  string  $symbol  The stock symbol (defaults to 'IBM').
     * @return \Inertia\Response Renders the 'Stocks/Index' Inertia component with stock data.
     */
    public function index(string $symbol = 'IBM')
    {
        $stockIterator = $this->stockApiService->fetchStockData($symbol);

        $stockData = $this->formatStockData($stockIterator);

        return Inertia::render('Stocks/Index', [
            'stocks' => $stockData,
            'symbol' => $symbol,
        ]);
    }

    /**
     * Displays the quote data for a given symbol.
     *
     * @param  string  $symbol  The stock symbol (defaults to 'IBM').
     * @return \Inertia\Response Renders the 'Stocks/Quote' Inertia component with quote data.
     */
    public function quote(string $symbol = 'IBM')
    {
        $stockIterator = $this->stockApiService->fetchQuoteData($symbol);
        $quoteData = $this->formatStockData($stockIterator)->first();

        return Inertia::render('Stocks/Quote', [
            'quote' => $quoteData,
            'symbol' => $symbol,
        ]);
    }

    /**
     * Formats the data from the StockApiIterator into a Collection
     *
     * @return Collection A collection containing the formatted data
     */
    private function formatStockData($iterator): Collection
    {
        return collect(iterator_to_array($iterator))->map(function ($item) {
            $formatted = ['date' => $item['date']];
            unset($item['date']);

            return array_merge($formatted, $item);
        });
    }
}
