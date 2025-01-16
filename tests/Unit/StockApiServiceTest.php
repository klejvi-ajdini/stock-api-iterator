<?php

namespace Tests\Unit;

use App\Services\StockApiIterator;
use App\Services\StockApiService;
use PHPUnit\Framework\TestCase;

class StockApiServiceTest extends TestCase
{
    public function test_fetch_stock_data_returns_stock_api_iterator_instance()
    {
        $jsonContent = json_encode([
            'Time Series (Daily)' => [
                '2024-05-14' => [
                    'open' => '170.00',
                    'high' => '172.50',
                    'low' => '169.00',
                    'close' => '172.00',
                ],
            ],
        ]);
        file_put_contents(base_path('resources/data/stock_data.json'), $jsonContent);

        $stockApiService = new StockApiService;
        $iterator = $stockApiService->fetchStockData('IBM');

        $this->assertInstanceOf(StockApiIterator::class, $iterator);
    }

    public function test_fetch_quote_data_returns_stock_api_iterator_instance()
    {
        $jsonContent = json_encode([
            'Global Quote' => [
                '01. symbol' => 'IBM',
                '02. open' => '170.00',
                '03. high' => '172.50',
                '04. low' => '169.00',
                '05. price' => '172.00',
                '06. volume' => '10000',
                '07. latest trading day' => '2024-05-14',
                '08. previous close' => '170.00',
                '09. change' => '2.00',
                '10. change percent' => '1.18%',
            ],
        ]);
        file_put_contents(base_path('resources/data/quote_data.json'), $jsonContent);

        $stockApiService = new StockApiService;
        $iterator = $stockApiService->fetchQuoteData('IBM');

        $this->assertInstanceOf(StockApiIterator::class, $iterator);
    }
}
