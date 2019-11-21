<?php


namespace Alexxd\StockData;


class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->singleton(Stock::class, function () {
            return new Stock(config('services.stock.key'));
        });

        $this->app->alias(Stock::class, 'stock');
    }

    public function provides()
    {
        return [Stock::class, 'stock'];
    }
}