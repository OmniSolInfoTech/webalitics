<?php

namespace Osit\Webalitics;

use Illuminate\Support\ServiceProvider;

class WebaliticsBaseServiceProvider extends ServiceProvider
{
    public function boot()
    {
        if($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->registerResources();
        $this->loadRoutesFrom(__DIR__.'/../src/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../src/resources/views', 'webalitics');
    }

    public function register()
    {
        $this->commands([
            Console\ProcessCommand::class,
        ]);
    }

    private function registerResources()
    {
        $this->loadMigrationsFrom(__DIR__."/database/migrations");
    }

    protected function registerPublishing()
    {
        $this->publishes([__DIR__."/config/webalitic.php" => config_path("webalitic.php")], "webalitics-config");
    }
}