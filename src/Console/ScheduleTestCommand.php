<?php

declare(strict_types=1);

namespace Beebmx\KirbScheduler\Console;

use Beebmx\KirbScheduler\Facades\Schedule;
use Kirby\CLI\CLI;

class ScheduleTestCommand
{
    public function __invoke(CLI $cli): void
    {
        $name = trim($cli->arg('name'));

        if (empty($name)) {
            $cli->error('You must provide the name of the scheduled task to test.');

            return;
        }

        Schedule::test($name);
    }
}
