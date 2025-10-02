<?php

use Beebmx\KirbScheduler\Facades\Schedule;
use Kirby\Filesystem\F;

Schedule::call(function () {
    F::write(fixtures('tmp/tasks.txt'), null);
})->everyMinute()->name('demo.tasks');
