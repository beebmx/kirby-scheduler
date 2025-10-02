<?php

use Beebmx\KirbScheduler\Schedule;

it('triggers a hook before schedule runs', function () {
    $callback = null;
    KirbyInstance(hooks: [
        'beebmx.scheduler.run:before' => function () use (&$callback) {
            $callback = 'before runs';
        },
    ]);

    Schedule::instance()->run();

    expect($callback)
        ->toEqual('before runs');
});

it('triggers a hook after schedule runs', function () {
    $callback = null;
    KirbyInstance(hooks: [
        'beebmx.scheduler.run:after' => function () use (&$callback) {
            $callback = 'after runs';
        },
    ]);

    Schedule::instance()->run();

    expect($callback)
        ->toEqual('after runs');
});
