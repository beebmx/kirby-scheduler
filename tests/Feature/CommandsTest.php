<?php

use Kirby\CLI\CLI;

beforeEach(function () {
    KirbyInstance();
});

it('exists a schedule:run command as plugins', function () {
    expect((new CLI)->commands()['plugins'])
        ->toContain('schedule:run');
});

test('the command schedule:run exists', function () {
    (new CLI)
        ->load('schedule:run');
})->throwsNoExceptions();

test('the command schedule:work exists', function () {
    (new CLI)
        ->load('schedule:work');
})->throwsNoExceptions();

test('the command schedule:list exists', function () {
    (new CLI)
        ->load('schedule:list');
})->throwsNoExceptions();

test('the command schedule:test exists', function () {
    (new CLI)
        ->load('schedule:test');
})->throwsNoExceptions();
