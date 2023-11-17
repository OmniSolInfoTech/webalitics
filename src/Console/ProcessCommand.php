<?php

namespace Osit\Webalitics\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

class ProcessCommand extends Command
{
    protected $signature = "webalitics:init";
    protected $description = "Running Webalitics process command.";

    /**
     * Initiates Webalitics in your app.
     *
     * @return void
     */
    public function handle()
    {
        // create a local copy of the config file
        $result = Process::run('php artisan vendor:publish --tag=webalitics-config');
        echo $result->output();

        // run migrate
        $result = Process::run('php artisan migrate');
        echo $result->output();
    }
}