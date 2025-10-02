<p align="center"><a href="https://github.com/beebmx/kirby-scheduler" rel="noopener"><img src="/.github/assets/logo.svg?raw=true" width="175" alt="Scheduler Logo"></a></p>

<p align="center">
<a href="https://github.com/beebmx/kirby-scheduler/actions"><img src="https://img.shields.io/github/actions/workflow/status/beebmx/kirby-scheduler/tests.yml?branch=main" alt="Build Status"></a>
<a href="https://packagist.org/packages/beebmx/kirby-scheduler"><img src="https://img.shields.io/packagist/dt/beebmx/kirby-scheduler" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/beebmx/kirby-scheduler"><img src="https://img.shields.io/packagist/v/beebmx/kirby-scheduler" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/beebmx/kirby-scheduler"><img src="https://img.shields.io/packagist/l/beebmx/kirby-scheduler" alt="License"></a>
</p>

# Scheduler for Kirby

This package enables [Laravel Scheduler](https://laravel.com/docs/scheduling) for your own Kirby applications.

According to `Laravel Scheduler` documentation, `Scheduler` offers a fresh approach to managing scheduled tasks on your server.
The scheduler allows you to fluently and expressively define your task schedule within your application itself.
When using the scheduler, only a **single cron entry** is needed on your server.

![Scheduler](/.github/assets/banner.jpg)

****

## Overview

- [1. Installation](#installation)
- [2. Usage](#usage)
- [3. Defining Schedules](#defining-schedules)
- [4. Running the Scheduler](#running-the-scheduler)
- [5. Scheduling Commands](#scheduling-commands)
- [6. Hooks](#hooks)
- [7. Options](#options)
- [8. License](#license)
- [9. Credits](#credits)

## Installation

```
composer require beebmx/kirby-scheduler
```

> [!IMPORTANT]
> There's no support for downloading the plugin manually. Please use Composer to install it.

Since `Scheduler` depends on `Laravel Scheduler`, and it uses some helpers from `Illuminate/Support`, you need to deactivate some helpers globally in your `index.php` file:

```php
/*
 * In your index.php file, and before rendering Kirby, add the following lines.
 */
const KIRBY_HELPER_E = false;
```

> [!NOTE]
> You can check the Kirby [documentation](https://getkirby.com/docs/reference/templates/helpers#deactivate-a-helper-globally) for more information about helpers.

## Usage

With Kirby `Scheduler` you have two ways to create your scheduled tasks:

### Closure

This is the easiest way to create your scheduled tasks.
You can define your scheduled tasks using a `closure` in the `config.php` file.

```php
use Beebmx\KirbScheduler\Schedule;

'beebmx.scheduler' => [
    'schedule' => function (Schedule $schedule) {
        $schedule->call(function() {
            //
        })->daily();
    },
],
```

### File tasks

If you have many scheduled tasks, you can create a dedicated file to manage them.
You can define the location of the file in your `config.php`:

```php
use Beebmx\KirbScheduler\Facades\Schedule;

'beebmx.scheduler' => [
    'tasks' => __DIR__.'/tasks.php'
],
```

> [!NOTE]
> You can define the filename and location as you wish; just make sure to provide the correct path.

Then, in the `tasks.php` file, you can define your scheduled tasks:

```php
<?php

use Beebmx\KirbScheduler\Facades\Schedule;

Schedule::call(function () {
    //
})->monthly();

```

> [!NOTE]
> You can use both methods (Closure and File) at the same time if you need them.

## Defining Schedules

### Calling a Closure

You can schedule a `closure` using the `call` method on the `Schedule` instance to perform a task.
In the following example, we schedule a `closure` to run daily at midnight:

```php
use Beebmx\KirbScheduler\Schedule;

'beebmx.scheduler' => [
    'schedule' => function (Schedule $schedule) {
        $schedule->call(function() {
            // Your task here
        })->daily();
    },
],
```

In addition to scheduling using closures, you may also schedule [invokable objects](https://secure.php.net/manual/en/language.oop5.magic.php#object.invoke).
Invokable objects are simple PHP classes that contain an `__invoke` method:

```php
$schedule->call(new PublishBlogEntry)->daily()->at('7:00');
```

> [!TIP]
> You can specify different frequencies intervals for your scheduled tasks, such as `hourly`, `daily`, `weekly`, `monthly`, etc.
> Refer to the [Schedule Frequency Options](https://laravel.com/docs/scheduling#schedule-frequency-options) for a complete list of available scheduling frequency options.

### Scheduling Shell Commands

The `exec` method may be used to issue a command to the operating system:

```php
use Beebmx\KirbScheduler\Facades\Schedule;

Schedule::exec('node /home/forge/script.js')->daily();
```

### Schedule Groups

When defining multiple scheduled tasks with similar configurations, you can use the group feature to avoid repeating the same settings for each task. Grouping tasks simplifies your code and ensures consistency across related tasks.

To create a group of scheduled tasks, invoke the desired task configuration methods, followed by the group method. The group method accepts a closure that is responsible for defining the tasks that share the specified configuration:

```php
use Beebmx\KirbScheduler\Facades\Schedule;

Schedule::daily()
    ->group(function () {
        Schedule::call(new PublishBlogEntry);
        Schedule::exec('cp storage/log/log.txt storage/log/log-'.date('Y-m-d').'.txt');
    });
```

### Timezones

Using the timezone method, you may specify that a scheduled task's time should be interpreted within a given timezone:

```php
use Beebmx\KirbScheduler\Facades\Schedule;

Schedule::call(function () {
    //
})->timezone('Europe/Berlin')->at('2:00');
```

If you are repeatedly assigning the same timezone to all of your scheduled tasks, you can specify which timezone should be assigned to all schedules by defining a `timezone` option within in `config.php` file:

```php
'beebmx.scheduler' => [
    'timezone' => 'Europe/Berlin',
],
```

## Running the Scheduler

The `schedule:run` command will evaluate all of your scheduled tasks and determine if they need to run based on the server's current time.

So, when using `Scheduler`, we only need to add a single cron configuration entry to your server that runs the `schedule:run` command every minute:

```cron
* * * * * /path/to/cli/bin/kirby schedule:run >> /dev/null 2>&1
```

> [!IMPORTANT]
> `schedule:run` command depends on [Kirby CLI](https://github.com/getkirby/cli).
> Make sure you have it installed and configured properly.

### Running the Scheduler Locally

Typically, you would not add a scheduler cron entry to your local development machine. Instead, you may use the `schedule:work` command.
This command will run in the foreground and invoke the scheduler every minute until you terminate the command.
When sub-minute tasks are defined, the scheduler will continue running within each minute to process those tasks:

```shell
kirby schedule:work
```

## Scheduling Commands

If you would like to view an overview of your scheduled tasks and the next time they are scheduled to run, you may use the `schedule:list` command:

```shell
kirby schedule:list
```

Sometimes you may want to test a specific scheduled task, without waiting for its scheduled time to arrive.
You may use the `schedule:test` command with a named scheduled task to run the task immediately:


```shell
kirby schedule:test task-name
```

It's important that you assign a name to your scheduled tasks using the `name` method:

```php
use Beebmx\KirbScheduler\Facades\Schedule;

Schedule::call(function () {
    //
})->monthly()->name('task-name');
```

## Hooks

`Scheduler` provides two hooks that allow you to execute code before and after the scheduled tasks are run.

- `beebmx.scheduler.run:before`: This hook is executed before the scheduled tasks are run.
- `beebmx.scheduler.run:after`: This hook is executed after the scheduled tasks are run.

## Options

| Option                    |   Type    | Default | Description                                             |
|:--------------------------|:---------:|:-------:|:--------------------------------------------------------|
| beebmx.scheduler.tasks    | `string`  |  null   | Define a path for external file.                        |
| beebmx.scheduler.schedule | `closure` |  null   | Define a closure with tasks to perform with the runner. |
| beebmx.scheduler.timezone | `string`  |   UTC   | Define a global timezone for the scheduler              |

Here's an example of a full use of the options from the `config.php` file:

```php
use Beebmx\KirbScheduler\Schedule;

'beebmx.scheduler' => [
    'timezone' => 'America/Mexico_City',
    'tasks' => __DIR__.'/tasks.php',
    'schedule' => function (Schedule $schedule) {
        $schedule->call(function() {
            // ...
        })->daily();
    },
],
```

## License

Licensed under the [MIT](LICENSE.md).

## Credits

- Fernando Gutierrez [@beebmx](https://github.com/beebmx)
- Jonas Ceja [@jonatanjonas](https://github.com/jonatanjonas) `logo`
- [All Contributors](../../contributors)
