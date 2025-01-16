<?php

declare(strict_types=1);

namespace App\Services;

use Exception;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Log;
use Iterator;

/**
 * Class StockApiIterator
 *
 * Implements the Iterator interface to handle fetching and iterating over
 * stock data from a (simulated) API, potentially paginated.
 * This class reads data from local json files.
 */
class StockApiIterator implements Iterator
{
    /**
     * @var array The parameters for the API request, including function and symbol.
     */
    private array $params;

    /**
     * @var int The current position of the iterator.
     */
    private int $position = 0;

    /**
     * @var array The data for the current "page" of results.
     */
    private array $data = [];

    /**
     * @var array An array containing the dates corresponding to each data point
     */
    private array $dates = [];

    /**
     * @var ?string The URL or ID for the next page of results, if available.
     */
    private ?string $nextPage;

    /**
     * @var string The name of the data file for the current data.
     */
    private string $filename;

    /**
     * StockApiIterator constructor.
     *
     * @param  array  $params  The parameters for the API request, including function and symbol.
     */
    public function __construct(array $params = [])
    {
        $this->params = $params;
        $this->filename = $this->determineFilename();
        $this->fetchPage();
    }

    /**
     * Determines the filename based on the API function being used.
     *
     * @return string The filename to be used for fetching data.
     *
     * @throws Exception If an invalid function parameter is provided.
     */
    private function determineFilename(): string
    {
        $function = $this->params['function'];
        switch ($function) {
            case 'TIME_SERIES_DAILY':
                $filename = 'stock_data.json';
                if (isset($this->params['page']) && $this->params['page'] == 2) {
                    $filename = 'stock_data_page_2.json';
                }

                return $filename;
            case 'GLOBAL_QUOTE':
                return 'quote_data.json';
            default:
                throw new Exception('Invalid function parameter.');
        }
    }

    /**
     * Fetches the next page of data from a local JSON file.
     *
     * @throws FileNotFoundException If the file does not exist.
     * @throws Exception If the data structure is invalid in the file.
     */
    private function fetchPage(): void
    {
        $filePath = base_path("resources/data/{$this->filename}");

        if (! file_exists($filePath)) {
            Log::error("Data file not found: {$this->filename}");
            throw new FileNotFoundException("Data file not found: {$this->filename} at path: {$filePath}");
        }

        $body = json_decode(file_get_contents($filePath), true);
        if (! is_array($body)) {
            Log::error("Invalid JSON in file: {$this->filename}");
            throw new Exception("Invalid JSON in file: {$this->filename}");
        }

        if (isset($body['Time Series (Daily)'])) {
            $this->data = array_values($body['Time Series (Daily)']);
            $this->dates = array_keys($body['Time Series (Daily)']);
        } else {
            $this->data = [$body['Global Quote']];
            $this->dates = [''];
        }

        $this->nextPage = isset($body['next_page']) ? (string) $body['next_page'] : null;
    }

    /**
     * Rewinds the iterator to the first element.
     */
    public function rewind(): void
    {
        $this->position = 0;
    }

    /**
     * Returns the current element of the iterator.
     *
     * @return mixed The current element, including the date, or null if invalid.
     */
    public function current(): mixed
    {
        if (! isset($this->data[$this->position])) {
            return null;
        }

        return [
            'date' => $this->dates[$this->position],
            ...$this->data[$this->position],
        ];
    }

    /**
     * Returns the key of the current element in the iterator.
     *
     * @return int The current key (position).
     */
    public function key(): int
    {
        return $this->position;
    }

    /**
     * Moves the iterator to the next element.
     * If it's the end of the current page, it fetches the next page, if available
     */
    public function next(): void
    {
        $this->position++;

        if ($this->position >= count($this->data) && $this->hasNext()) {
            $this->params['page'] = $this->nextPage;
            $this->filename = $this->determineFilename();
            $this->fetchPage();
            $this->position = 0;
        }
    }

    /**
     * Checks if the current position is valid.
     *
     * @return bool True if the position is valid, false otherwise.
     */
    public function valid(): bool
    {
        return isset($this->data[$this->position]);
    }

    /**
     * Checks if there is a next page available.
     *
     * @return bool True if a next page is available, false otherwise.
     */
    public function hasNext(): bool
    {
        return isset($this->nextPage);
    }
}
