<?php

namespace App\Commands;

use App\Commands\AutomatedCommand;
use App\Commands\InteractiveCommand;
use App\Repository\GetFileContent;
use App\Repository\HandleInput;

/**
 * Command to resolve Automated and Interactive Commands
 */
class ParkingLotCommand
{
    /**
     * Method to initiate the command
     *
     * @return void
     */
    public function run(): void
    {
        echo shell_exec('vendor/bin/phpunit tests/*');

        if (count($_SERVER["argv"]) > 1) {
            (new AutomatedCommand(new HandleInput, new GetFileContent))->run();
        } else {
            (new InteractiveCommand(new HandleInput))->run();
        }

    }

}
