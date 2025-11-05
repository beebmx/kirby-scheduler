<?php
declare(strict_types=1);
namespace Beebmx\KirbScheduler\Facades;

use Beebmx\KirbScheduler\Schedule as ConsoleSchedule;
use Kirby\Toolkit\Facade;

/**
 * @method static \Beebmx\KirbScheduler\Application container()
 * @method static \Illuminate\Console\Scheduling\CallbackEvent call(callable $callback, array $parameters = [])
 * @method static \Illuminate\Console\Scheduling\Event exec(string $command, array $parameters = [])
 * @method static \Illuminate\Console\Scheduling\Event[] events()
 * @method static \Illuminate\Console\Scheduling\Schedule scheduler()
 * @method static bool isReady()
 * @method static bool isRunning()
 * @method static bool isStarted()
 * @method static bool isTerminated()
 * @method static string formatCommandString(string $command)
 * @method static void group(\Closure $events)
 * @method static void list()
 * @method static void run()
 * @method static void test()
 * @method static \Illuminate\Support\Collection dueEvents()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes at(string $time)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes between(string $startTime, string $endTime)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes cron(string $expression)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes daily()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes dailyAt(string $time)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes days(mixed $days)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes description(string $description)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes environments(mixed $environments)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes evenInMaintenanceMode()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyFifteenMinutes()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyFifteenSeconds()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyFiveMinutes()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyFiveSeconds()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyFourHours(array|string|int $offset = 0)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyFourMinutes()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyMinute()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyOddHour(array|string|int $offset = 0)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everySecond()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everySixHours(array|string|int $offset = 0)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyTenMinutes()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyTenSeconds()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyThirtyMinutes()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyThirtySeconds()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyThreeHours(array|string|int $offset = 0)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyThreeMinutes()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyTwentySeconds()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyTwoHours(array|string|int $offset = 0)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyTwoMinutes()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes everyTwoSeconds()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes fridays()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes hourly()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes hourlyAt(array|string|int|int[] $offset)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes lastDayOfMonth(string $time = '0:0')
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes mondays()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes monthly()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes monthlyOn(int $dayOfMonth = 1, string $time = '0:0')
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes name(string $description)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes onOneServer()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes quarterly()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes quarterlyOn(int $dayOfQuarter = 1, string $time = '0:0')
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes runInBackground()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes saturdays()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes skip(\Closure|bool $callback)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes sundays()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes thursdays()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes timezone(\UnitEnum|\DateTimeZone|string $timezone)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes tuesdays()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes twiceDaily(int $first = 1, int $second = 13)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes twiceDailyAt(int $first = 1, int $second = 13, int $offset = 0)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes twiceMonthly(int $first = 1, int $second = 16, string $time = '0:0')
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes unlessBetween(string $startTime, string $endTime)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes wednesdays()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes weekdays()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes weekends()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes weekly()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes weeklyOn(mixed $dayOfWeek, string $time = '0:0')
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes when(\Closure|bool $callback)
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes yearly()
 * @method static \Illuminate\Console\Scheduling\PendingEventAttributes yearlyOn(int $month = 1, int|string $dayOfMonth = 1, string $time = '0:0')
 *
 * @see \Beebmx\KirbScheduler\Schedule
 */
final class Schedule extends Facade
{
    public static function instance(): ConsoleSchedule
    {
        return ConsoleSchedule::instance();
    }
}
