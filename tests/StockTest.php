<?php


namespace Alexxd\StockData\Tests;


use Alexxd\StockData\Exceptions\HttpException;
use Alexxd\StockData\Exceptions\InvalidArgumentException;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use GuzzleHttp\Psr7\Response;
use Mockery\Matcher\AnyArgs;
use PHPUnit\Framework\TestCase;
use Alexxd\StockData\Stock;

class StockTest extends TestCase
{

    public function testGetIntradayStockWithInvalidInterval()
    {
        $s = new Stock('mock-key');

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage("Invalid interval: foo.(The following values are supported: 1, 5, 15, 30, 60)");

        $s->getIntradayStock('X', 'foo');

        $this->fail('Failed to assert getIntradayStock throw exception with invalid argument.');
    }

    public function testGetIntradayStockWithInvalidFormat()
    {
        $s = new Stock('mock-key');

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage("Invalid response format: array.(The following values are supported: csv,json)");

        $s->getIntradayStock('X', '5', 'array');

        $this->fail('Failed to assert getIntradayStock throw exception with invalid argument.');
    }

    public function testGetIntradayStockWithInvalidOutputSize()
    {
        $s = new Stock('mock-key');

        $this->expectException(InvalidArgumentException::class);

        $this->expectExceptionMessage("Invalid output size: foo.(The following values are supported: compact,full)");

        $s->getIntradayStock('X', '5', 'json', 'foo');

        $this->fail('Failed to assert getIntradayStock throw exception with invalid argument.');
    }

    public function testGetIntradayStock()
    {
        $response = new Response(200, [], '{"success": true}');

        $client = \Mockery::mock(Client::class);

        $client->allows()->get(sprintf('https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY
        &symbol=%s&interval=%s&datatype=%s&outputsize=%s&apikey=%s',
            'X', '5min', 'json', 'compact', 'mock-key'))
            ->andReturn($response);

        $s = \Mockery::mock(Stock::class, ['mock-key'])->makePartial();

        $s->allows()->getHttpClient()->andReturn($client);

        $this->assertSame('{"success": true}', $s->getIntradayStock('X'));

        $response = new Response(200, [],'timestamp,open,high,low,close,volume');

        $client = \Mockery::mock(Client::class);

        $client->allows()->get(sprintf('https://www.alphavantage.co/query?function=TIME_SERIES_INTRADAY
        &symbol=%s&interval=%s&datatype=%s&outputsize=%s&apikey=%s',
            'X', '5min', 'csv', 'compact', 'mock-key'))
            ->andReturn($response);

        $s = \Mockery::mock(Stock::class, ['mock-key'])->makePartial();

        $s->allows()->getHttpClient()->andReturn($client);

        $this->assertSame('timestamp,open,high,low,close,volume', $s->getIntradayStock('X','5','csv'));
    }

    public function testGetIntradayStockWithGuzzleRuntimeException()
    {
        $client = \Mockery::mock(Client::class);
        $client->allows()
            ->get(new AnyArgs())
            ->andThrow(new \Exception('request timeout'));

        $s = \Mockery::mock(Stock::class, ['mock-key'])->makePartial();
        $s->allows()->getHttpClient()->andReturn($client);

        $this->expectException(HttpException::class);
        $this->expectExceptionMessage('request timeout');

        $s->getIntradayStock('X');
    }

    public function testGetHttpClient()
    {
        $s = new Stock('mock-key');

        $this->assertInstanceOf(ClientInterface::class, $s->getHttpClient());
    }

    public function testSetGuzzleOptions()
    {
        $s = new Stock('mock-key');

        // 设置参数前，timeout 为 null
        $this->assertNull($s->getHttpClient()->getConfig('timeout'));

        // 设置参数
        $s->setGuzzleOptions(['timeout' => 5000]);

        // 设置参数后，timeout 为 5000
        $this->assertSame(5000, $s->getHttpClient()->getConfig('timeout'));
    }
}