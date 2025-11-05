<?php

declare(strict_types=1);

namespace Beebmx\KirbScheduler\Console;

use Beebmx\KirbScheduler\Facades\Schedule;
use Illuminate\Support\Carbon;
use Kirby\CLI\CLI;

final class ScheduleWorkCommand
{
    public function __invoke(CLI $cli): void
    {
        $cli->out('Running scheduled tasks.');

        $lastExecutionStartedAt = Carbon::now()->subMinutes(10);

        while (true) {
            usleep(100 * 1000);

            if (Carbon::now()->second === 0 &&
                ! Carbon::now()->startOfMinute()->equalTo($lastExecutionStartedAt)) {
                $lastExecutionStartedAt = Carbon::now()->startOfMinute();
                Schedule::run();
            }
        }
    }
}
