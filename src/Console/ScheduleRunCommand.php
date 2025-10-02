<?php

declare(strict_types=1);

namespace Beebmx\KirbScheduler\Console;

use Beebmx\KirbScheduler\Facades\Schedule;
use Kirby\CLI\CLI;

class ScheduleRunCommand
{
    public function __invoke(CLI $cli): void
    {
        $cli->out('Running scheduled tasks.');

        Schedule::run();
    }
}
