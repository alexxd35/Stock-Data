<?php


namespace Alexxd\StockData;


use Alexxd\StockData\Exceptions\HttpException;
use Alexxd\StockData\Exceptions\InvalidArgumentException;
use GuzzleHttp\Client;

class Stock
{
    protected $key;
    protected $guzzleOptions = [];

    public function __construct(string $key)
    {
        $this->key = $key;
    }

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions(array $options)
    {
        $this->guzzleOptions = $options;
    }

    public function getIntradayStock($symbol, $interval = '5', $format = 'json', $output_size = 'compact')
    {
        if (!\in_array((string)$interval, ['1', '5', '15', '30', '60'])) {
            throw new InvalidArgumentException('Invalid interval: ' . $interval . '.(The following values are supported: 1, 5, 15, 30, 60)');
        }
        if (!\in_array(\strtolower($format), ['csv', 'json'])) {
            throw new InvalidArgumentException('Invalid response format: ' . $format . '.(The following values are supported: csv,json)');
        }
        if (!\in_array(\strtolower($output_size), ['compact', 'full'])) {
            throw new InvalidArgumentException('Invalid output size: ' . $output_size . '.(The following values are supported: compact,full)');
        }

        $url = sprintf('https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY&symbol=%s&interval=%s&datatype=%s&outputsize=%s&apikey=%s',
            $symbol, $interval . 'min', $format, $output_size, $this->key);

        try {
            $response = $this->getHttpClient()->get($url)->getBody()->getContents();
            return $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }

    }
}