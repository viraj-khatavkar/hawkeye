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

    public function boot()
    {
        // Register commands
        $this->commands('command.hawkeye.migration');

        $this->publishes([
            __DIR__ . '/config.php' => config_path('hawkeye.php'),

        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */

    public function register()
    {
        $this->app->bind('hawkeye', function ($app) {
            return new Hawkeye();
        });

        $this->app->singleton('command.hawkeye.migration', function ($app) {
            return new \Viraj\Hawkeye\commands\MigrationCommand();
        });
    }

    /**
     * Get the services provided.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'command.hawkeye.migration',
        ];
    }
}
