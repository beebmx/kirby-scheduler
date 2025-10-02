<?php

use Beebmx\KirbScheduler\Schedule;
use Illuminate\Support\Carbon;

beforeEach(function () {
    Carbon::setTestNow(Carbon::now());
});

test('default timezone is UTC', function () {
    KirbyInstance();

    expect(Schedule::instance()->scheduler()->command('schedule:run')->timezone)
        ->toEqual('UTC');
});

it('can set timezone', function () {
    KirbyInstance(scheduler: [
        'timezone' => 'America/Mexico_City',
    ]);

    expect(Schedule::instance()->scheduler()->command('schedule:run')->timezone)
        ->toEqual('America/Mexico_City');
});

afterEach(function () {
    Schedule::destroy();
});
