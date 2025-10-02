<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "pest()" function to bind a different classes or traits.
|
*/

use Kirby\Cms\App;

pest()->extend(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function KirbyInstance(array $roots = [], array $scheduler = [], array $hooks = []): App
{
    App::$enableWhoops = false;

    return new App([
        'roots' => array_merge([
            'index' => '/dev/null',
            'base' => $base = dirname(__DIR__).'/tests/Fixtures',
            'cache' => $base.'/cache',
        ], $roots),
        'commands' => require dirname(__DIR__).'/extensions/commands.php',
        'options' => [
            'hooks' => $hooks,
            'beebmx.scheduler' => array_merge(
                require dirname(__DIR__).'/extensions/options.php',
                $scheduler
            ),
        ],
    ]);
}

function fixtures(string $path): string
{
    return dirname(__DIR__).'/tests/Fixtures/'.$path;
}
