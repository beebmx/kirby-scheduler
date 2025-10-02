<?php

use Beebmx\KirbScheduler\Console\ScheduleListCommand;
use Beebmx\KirbScheduler\Console\ScheduleRunCommand;
use Beebmx\KirbScheduler\Console\ScheduleTestCommand;
use Beebmx\KirbScheduler\Console\ScheduleWorkCommand;

return [
    'schedule:run' => [
        'description' => 'Run the scheduled commands',
        'command' => new ScheduleRunCommand,
    ],
    'schedule:work' => [
        'description' => 'Start the schedule worker',
        'command' => new ScheduleWorkCommand,
    ],
    'schedule:list' => [
        'description' => 'List all scheduled tasks',
        'command' => new ScheduleListCommand,
    ],
    'schedule:test' => [
        'description' => 'Run a scheduled command',
        'command' => new ScheduleTestCommand,
        'args' => [
            'name' => [
                'description' => 'The name of the command',
                // 'longPrefix' => 'name',
                // 'prefix' => 'n',
            ],
        ],
    ],
];
