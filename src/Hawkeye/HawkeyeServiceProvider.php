<?php namespace Viraj\Hawkeye;

use Illuminate\Support\ServiceProvider;

class HawkeyeServiceProvider extends ServiceProvider
{
    public $app;

    public function register()
    {
        $this->app->bind('hawkeye', function ($app) {
            return new Hawkeye($app);
        });
    }
}