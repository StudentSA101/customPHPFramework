<?php

namespace App\Commands;

use App\Contracts\HandleCommandDataInterface;

class InteractiveCommand
{
    private $handle;

    public function __construct(HandleCommandDataInterface $handle)
    {
        $this->handle = $handle;
    }

    public function run(bool $test = false): void
    {
        echo "\nWelcome to the Parking Lot Terminal\n\n";

        $input = lcfirst(trim(preg_replace('/\n/', '', fread(STDIN, 80))));
        $parameters = preg_split('/\s/', $input);

        while ($input !== 'exit') {
            if (count($parameters) > 1) {
                echo $this->handle->determine($parameters[0], [$parameters[1]]);
            } else if (count($parameters) === 1) {
                echo $this->handle->determine($input, []);
            }
            $input = lcfirst(trim(preg_replace('/\n/', '', fread(STDIN, 80))));
            $parameters = preg_split('/\s/', $input);

        }
        echo "The Command Shell has been terminated ";
    }

}
