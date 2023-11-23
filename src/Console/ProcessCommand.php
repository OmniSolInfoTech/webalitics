<?php

namespace Osit\Webalitics\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Process;

/**
 * ProcessCommand - main class
 *
 * ProcessCommand
 * distributed under the MIT License
 *
 * @author  Dominic Moeketsi developer@osit.co.za
 * @company OmniSol Information Technology (PTY) LTD
 * @version 1.00
 */
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
        $result = Process::run("php artisan vendor:publish --tag=webalitics-config");
        echo $result->output();

        // run migrate
        $result = Process::run("php artisan migrate");
        echo $result->output();
    }
}