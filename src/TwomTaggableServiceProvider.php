<?php


namespace Twom\Taggable;


use Illuminate\Support\ServiceProvider;

class TwomTaggableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->registerConfig();
        $this->registerMigrations();
    }

    public function register()
    {
        //
    }

    protected function registerMigrations()
    {
        $this->publishes([
            realpath(__DIR__ . '/Migrations') => database_path('migrations')
        ], 'migrations');
        $this->loadMigrationsFrom(realpath(__DIR__ . '/Migrations'));
    }

    protected function registerConfig()
    {
        $this->publishes([
            realpath(__DIR__ . "/../config/taggable.php") => config_path('taggable.php')
        ], 'config');
        $this->mergeConfigFrom(realpath(__DIR__ . '/../config/taggable.php'), 'taggable');
    }
}
