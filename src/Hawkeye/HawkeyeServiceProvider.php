<?php namespace Viraj\Hawkeye;

use Illuminate\Support\ServiceProvider;

class HawkeyeServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('hawkeye', function ($app) {
            return new Hawkeye(new \Viraj\Hawkeye\FileRepository());
        });
    }
}