<?php

namespace Alexxd\StockData;

use Alexxd\StockData\Exceptions\HttpException;
use Alexxd\StockData\Exceptions\InvalidArgumentException;
use GuzzleHttp\Client;

class Stock
{
    protected $key;

    protected $guzzleOptions = [];

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function getHttpClient()
    {
        return new Client($this->guzzleOptions);
    }

    public function setGuzzleOptions($options)
    {
        $this->guzzleOptions = $options;
    }

    public function stockFactory($data)
    {
        if (!is_array($data)) {
            throw new InvalidArgumentException('Invalid data: ' . $data . '.(Data must be an array)');
        }
        foreach (['function', 'symbol'] as $value) {
            if (!array_key_exists($value, $data)) {
                throw new InvalidArgumentException('Array key: ' . $value . ' must be exists');
            }
        }
        $url = 'https://www.alphavantage.co/query';
        $query = array_filter([
            'function' => strtoupper($data['function']),
            'symbol' => $data['symbol'],
            'datatype' => $data['format'],
            'outputsize' => $data['output_size'],
            'apikey' => $this->key
        ]);
        if ('TIME_SERIES_INTRADAY' === strtoupper($data['function'])) {
            if (!array_key_exists('interval', $data)) {
                throw new InvalidArgumentException('Array key: interval must be exists in this function');
            }
            $query['interval'] = $data['interval'] . 'min';
        }
        try {
            $response = $this->getHttpClient()->get($url, [
                'query' => $query,
            ])->getBody()->getContents();

            return $response;
        } catch (\Exception $e) {
            throw new HttpException($e->getMessage(), $e->getCode(), $e);
        }


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
        $data = [
            'function' => 'TIME_SERIES_INTRADAY',
            'symbol' => $symbol,
            'interval' => $interval,
            'format' => $format,
            'output_size' => $output_size
        ];
        return $this->stockFactory($data);
    }

    public function getDailyStock($symbol, $format = 'json', $output_size = 'compact')
    {
        if (!\in_array(\strtolower($format), ['csv', 'json'])) {
            throw new InvalidArgumentException('Invalid response format: ' . $format . '.(The following values are supported: csv,json)');
        }
        if (!\in_array(\strtolower($output_size), ['compact', 'full'])) {
            throw new InvalidArgumentException('Invalid output size: ' . $output_size . '.(The following values are supported: compact,full)');
        }
        $data = [
            'function' => 'TIME_SERIES_DAILY',
            'symbol' => $symbol,
            'format' => $format,
            'output_size' => $output_size
        ];
        return $this->stockFactory($data);
    }

    public function getWeeklyStock($symbol, $format = 'json', $output_size = 'compact')
    {
        if (!\in_array(\strtolower($format), ['csv', 'json'])) {
            throw new InvalidArgumentException('Invalid response format: ' . $format . '.(The following values are supported: csv,json)');
        }
        if (!\in_array(\strtolower($output_size), ['compact', 'full'])) {
            throw new InvalidArgumentException('Invalid output size: ' . $output_size . '.(The following values are supported: compact,full)');
        }
        $data = [
            'function' => 'TIME_SERIES_WEEKLY',
            'symbol' => $symbol,
            'format' => $format,
            'output_size' => $output_size
        ];
        return $this->stockFactory($data);
    }

    public function getMonthlyStock($symbol, $format = 'json', $output_size = 'compact')
    {
        if (!\in_array(\strtolower($format), ['csv', 'json'])) {
            throw new InvalidArgumentException('Invalid response format: ' . $format . '.(The following values are supported: csv,json)');
        }
        if (!\in_array(\strtolower($output_size), ['compact', 'full'])) {
            throw new InvalidArgumentException('Invalid output size: ' . $output_size . '.(The following values are supported: compact,full)');
        }
        $data = [
            'function' => 'TIME_SERIES_MONTHLY',
            'symbol' => $symbol,
            'format' => $format,
            'output_size' => $output_size
        ];
        return $this->stockFactory($data);
    }
}
