<?php

use Beebmx\KirbScheduler\Schedule;
use Illuminate\Support\Carbon;
use Kirby\Filesystem\F;

beforeEach(function () {
    Carbon::setTestNow(Carbon::now());
});

it('can run schedule', function () {
    KirbyInstance();

    Schedule::instance()->run();
})->throwsNoExceptions();

it('calls schedule callback', function () {
    $file = fixtures('tmp/'.Carbon::now()->getTimestampMs().'.txt');

    KirbyInstance(scheduler: [
        'schedule' => function (Schedule $schedule) use ($file) {
            $schedule->call(function () use ($file) {
                F::write($file, null);
            })->everyMinute()->name('demo.file');
        },
    ]);

    Schedule::instance()->run();

    expect(F::exists($file))
        ->toBeTrue();
});

it('calls schedule commands path', function () {
    KirbyInstance(scheduler: [
        'tasks' => __DIR__.'/../Fixtures/routes/tasks.php',
    ]);

    Schedule::instance()->run();

    expect(F::exists(fixtures('tmp/tasks.txt')))
        ->toBeTrue();
});

afterEach(function () {
    Schedule::destroy();
});

afterAll(function () {
    foreach (glob(fixtures('tmp/*')) as $tmp) {
        F::remove($tmp);
    }
});
