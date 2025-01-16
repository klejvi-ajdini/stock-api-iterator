<?php

namespace Tests\Unit;

use App\Services\StockApiIterator;
use Tests\TestCase;

class StockApiIteratorTest extends TestCase
{
    public function test_iterator_with_single_page()
    {
        $jsonContent = json_encode([
            'Time Series (Daily)' => [
                '2024-05-14' => [
                    '1. open' => '170.00',
                    '2. high' => '172.50',
                    '3. low' => '169.00',
                    '4. close' => '172.00',
                ],
            ],
        ]);
        file_put_contents(base_path('resources/data/stock_data.json'), $jsonContent);

        $iterator = new StockApiIterator(['symbol' => 'IBM', 'function' => 'TIME_SERIES_DAILY']);

        $data = [];
        foreach ($iterator as $item) {
            $data[] = $item;
        }

        $this->assertCount(1, $data);
    }

    public function test_iterator_with_multiple_pages()
    {
        $jsonContentPage1 = json_encode([
            'Time Series (Daily)' => [
                '2024-05-14' => [
                    '1. open' => '170.00',
                    '2. high' => '172.50',
                    '3. low' => '169.00',
                    '4. close' => '172.00',
                ],
            ],
            'next_page' => 2,
        ]);
        file_put_contents(base_path('resources/data/stock_data.json'), $jsonContentPage1);

        $jsonContentPage2 = json_encode([
            'Time Series (Daily)' => [
                '2024-05-10' => [
                    '1. open' => '162.00',
                    '2. high' => '166.50',
                    '3. low' => '160.00',
                    '4. close' => '166.00',
                ],
            ],
        ]);
        file_put_contents(base_path('resources/data/stock_data_page_2.json'), $jsonContentPage2);

        $iterator = new StockApiIterator(['symbol' => 'IBM', 'function' => 'TIME_SERIES_DAILY']);

        $data = [];
        foreach ($iterator as $item) {
            $data[] = $item;
        }

        $this->assertCount(2, $data);
    }

    public function test_iterator_global_quote()
    {
        $jsonContent = json_encode([
            'Global Quote' => [
                '01. symbol' => 'IBM',
                '02. open' => '170.00',
                '03. high' => '172.50',
                '04. low' => '169.00',
                '05. price' => '172.00',
                '06. volume' => '10000',
                '07. latestDay' => '2024-05-14',
                '08. previous close' => '170.00',
                '09. change' => '2.00',
                '10. change percent' => '1.18%',
            ],
        ]);
        file_put_contents(base_path('resources/data/quote_data.json'), $jsonContent);

        $iterator = new StockApiIterator(['symbol' => 'IBM', 'function' => 'GLOBAL_QUOTE']);

        $data = [];
        foreach ($iterator as $item) {
            $data[] = $item;
        }

        $this->assertCount(1, $data);
        $this->assertEquals('IBM', $data[0]['01. symbol']);

    }
}
