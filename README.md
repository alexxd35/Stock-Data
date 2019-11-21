<h1 align="center"> Stock-Data </h1>

<p align="center"> This package provide realtime and historical global equity data in 4 different temporal resolutions:(1)daily,(2)weekly,(3)monthly,and(4)intraday.Daily,weekly,and monthly time series contain 20+ years of historical data.</p>


## Installing

```shell
$ composer require alexxd/stock-data -vvv
```


## Configuration

Before using this extension, you need to go to the [Alphavantage](https://www.alphavantage.co/) to register your account, then create an app to get the API Key for your app.
## Usage
```
use Alexxd\StockData\Stock;

$key = 'xxxxxxxxxxxxxxxxxxxxxxxxxxx';

$stock = new Stock($key);
```
## Get IntraDay stock data
```
$response = $stock->getIntradayStock('AAPL');
```
Example:
```

{
    "Meta Data": {
        "1. Information": "Intraday (5min) open, high, low, close prices and volume",
        "2. Symbol": "AAPL",
        "3. Last Refreshed": "2019-11-20 16:00:00",
        "4. Interval": "5min",
        "5. Output Size": "Compact",
        "6. Time Zone": "US/Eastern"
    },
    "Time Series (5min)": {
        "2019-11-20 16:00:00": {
            "1. open": "262.9000",
            "2. high": "263.0700",
            "3. low": "262.7800",
            "4. close": "262.7900",
            "5. volume": "707185"
        },
        "2019-11-20 15:55:00": {
            "1. open": "262.7200",
            "2. high": "262.8900",
            "3. low": "262.6222",
            "4. close": "262.8900",
            "5. volume": "386239"
        },
        "2019-11-20 15:50:00": {
            "1. open": "262.7100",
            "2. high": "262.9600",
            "3. low": "262.6800",
            "4. close": "262.7250",
            "5. volume": "329007"
        },
        ...
    }
}
```
## Parameters
```
getIntradayStock($symbol, $interval = '5', $format = 'json',$output_size='compact')
```
**symbol** :The name of the equity of your choice. For example:AAPL

**interval**:Time interval between two consecutive data points in the time series. The following values are supported: 1, 5, 15, 30, 60

**format**:By default, format=json. Strings **json** and **csv** are accepted with the following specifications: json returns the intraday time series in JSON format; csv returns the time series as a CSV (comma separated value) file.

**output_size**:By default, output_size=compact. Strings **compact** and **full** are accepted with the following specifications: compact returns only the latest 100 data points in the intraday time series; full returns the full-length intraday time series. The "compact" option is recommended if you would like to reduce the data size of each API call.

## Used in Laravel

The same installation is used in Laravel, and the configuration is written in config/services.php:
```
    .
    .
    .
     'stock' => [
        'key' => env('STOCK_API_KEY'),
    ],
```
Then configure STOCK_API_KEY in .env :
```
STOCK_API_KEY=xxxxxxxxxxxxxxxxxxxxx
```
There are two ways to get an Alexxd\StockData\Stock instance:

#### Method parameter injection

```
    .
    .
    .
    public function test(Stock $stock) 
    {
        $response = $stock->getIntraDayStock('AAPL');
    }
    .
    .
    .
```

#### Service name access

```
    .
    .
    .
    public function test() 
    {
        $response = app('stock')->getIntraDayStock('AAPL');
    }
    .
    .
    .
```
## Contributing

You can contribute in one of three ways:

1. File bug reports using the [issue tracker](https://github.com/alexxd/stock-data/issues).
2. Answer questions or fix bugs on the [issue tracker](https://github.com/alexxd/stock-data/issues).
3. Contribute new features or update the wiki.



## License

MIT