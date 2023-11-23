<?php

namespace Osit\Webalitics;

use Illuminate\Support\ServiceProvider;

/**
 * WebaliticsBaseServiceProvider - main class
 *
 * WebaliticsBaseServiceProvider
 * distributed under the MIT License
 *
 * @author  Dominic Moeketsi developer@osit.co.za
 * @company OmniSol Information Technology (PTY) LTD
 * @version 1.00
 */
class WebaliticsBaseServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     *
     * @return void
     */
    public function boot(): void
    {
        if($this->app->runningInConsole()) {
            $this->registerPublishing();
        }

        $this->registerResources();
        $this->loadRoutesFrom(__DIR__.'/../src/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/../src/resources/views', 'webalitics');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register(): void
    {
        $this->commands([
            Console\ProcessCommand::class,
        ]);
    }

    /**
     * Register the package resources.
     *
     * @return void
     */
    private function registerResources(): void
    {
        $this->loadMigrationsFrom(__DIR__."/database/migrations");
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    protected function registerPublishing(): void
    {
        $this->publishes([__DIR__."/config/webalitic.php" => config_path("webalitic.php")], "webalitics-config");
        $this->publishes([ __DIR__."/resources/webalitic-assets" => public_path("webalitic-assets"),], "webalitics-config");
    }
}