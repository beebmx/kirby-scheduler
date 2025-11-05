<?php

declare(strict_types=1);

namespace Beebmx\KirbScheduler;

use BadMethodCallException;
use Beebmx\KirbScheduler\Cache\Manager;
use Beebmx\KirbScheduler\Enums\Process;
use Beebmx\KirbScheduler\Exceptions\Handler;
use Closure;
use Illuminate\Cache\Repository;
use Illuminate\Config\Repository as Config;
use Illuminate\Console\Application as Console;
use Illuminate\Console\Scheduling\CallbackEvent;
use Illuminate\Console\Scheduling\Event;
use Illuminate\Console\Scheduling\PendingEventAttributes;
use Illuminate\Console\Scheduling\Schedule as LaravelSchedule;
use Illuminate\Console\Scheduling\ScheduleListCommand;
use Illuminate\Console\Scheduling\ScheduleRunCommand;
use Illuminate\Console\Scheduling\ScheduleTestCommand;
use Illuminate\Container\Container;
use Illuminate\Contracts\Cache\Factory as CacheFactory;
use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Collection;
use Kirby\Cms\App as Kirby;
use Kirby\Filesystem\F;
use RuntimeException;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\BufferedOutput;

final class Schedule
{
    protected Container $container;

    protected LaravelSchedule $schedule;

    protected static ?Schedule $instance;

    protected Process $status = Process::READY;

    public function __construct()
    {
        $this->setup();
    }

    private function setup(): void
    {
        $this->container = Application::getInstance();

        $this->container->instance('app', $this->container);

        $this->container->instance('config', new Config([
            'cache' => [
                'default' => 'array',
                'stores' => [
                    'array' => [
                        'driver' => 'array',
                    ],
                    'file' => [
                        'driver' => 'file',
                        'path' => Kirby::instance()->root('cache').'/schedule',
                        'lock_path' => Kirby::instance()->root('cache').'/schedule',
                    ],
                ],
            ],
        ]));

        $this->container->bind(ExceptionHandler::class, Handler::class);

        $this->container->singleton(Dispatcher::class, function (Container $container) {
            return new \Illuminate\Events\Dispatcher($container);
        });

        $this->container->instance('files', new Filesystem);

        $this->container->singleton(CacheFactory::class, function (Container $container) {
            return new Manager($container);
        });

        $this->container->singleton(Cache::class, function (Container $container) {
            return new Repository($container->make(CacheFactory::class)->driver()->getStore());
        });

        $this->container->singleton(LaravelSchedule::class, function (): LaravelSchedule {
            return tap(new LaravelSchedule(Kirby::instance()->option('beebmx.scheduler.timezone', 'UTC')), function (LaravelSchedule $schedule): void {
                $schedule->useCache('file');
            });
        });

        $this->schedule = $this->container->make(LaravelSchedule::class);
    }

    /**
     * Run the scheduled commands.
     */
    public function run(): void
    {
        if (empty($this->events())) {
            $this->prepare();
        }

        $this->status = Process::STARTED;

        $scheduler = new ScheduleRunCommand;

        $scheduler->setLaravel(Application::getInstance());
        $input = new ArrayInput([]);
        $output = new BufferedOutput;

        try {
            Kirby::instance()->trigger('beebmx.scheduler.run:before');

            $scheduler->run($input, $output);
        } finally {
            Kirby::instance()->trigger('beebmx.scheduler.run:after');

            $this->status = Process::TERMINATED;
            echo PHP_EOL.$output->fetch();
        }
    }

    /**
     * List the scheduled commands.
     */
    public function list(): void
    {
        if (empty($this->events())) {
            $this->prepare();
        }

        $list = new ScheduleListCommand;

        $list->setLaravel(Application::getInstance());
        $input = new ArrayInput([
            '--timezone' => Kirby::instance()->option('beebmx.scheduler.timezone', 'UTC'),
        ]);

        $output = new BufferedOutput;

        $list->run($input, $output);

        echo PHP_EOL.$output->fetch();
    }

    public function test(string $command): void
    {
        if (empty($this->events())) {
            $this->prepare();
        }

        $test = new ScheduleTestCommand;

        $test->setLaravel(Application::getInstance());
        $input = new ArrayInput([
            '--name' => $command,
        ]);

        $output = new BufferedOutput;

        $test->run($input, $output);

        echo PHP_EOL.$output->fetch();
    }

    public function formatCommandString(string $command): string
    {
        return sprintf('%s %s', Console::phpBinary(), $command);
    }

    /**
     * Get all the events on the schedule.
     *
     * @return Event[]
     */
    public function events(): array
    {
        return $this->schedule->events();
    }

    /**
     * Get all the events on the schedule that are due.
     */
    public function dueEvents(): Collection
    {
        return $this->schedule->dueEvents($this->container);
    }

    /**
     * Add a new callback event to the schedule.
     */
    public function call(string|callable $callback, array $parameters = []): CallbackEvent
    {
        return $this->schedule->call($callback, $parameters);
    }

    /**
     * Add a new command event to the schedule.
     */
    public function exec(string $command, array $parameters = []): Event
    {
        return $this->schedule->exec($command, $parameters);
    }

    /**
     * Create new schedule group.
     *
     * @throws RuntimeException
     */
    public function group(Closure $events): void
    {
        $this->schedule->group($events);
    }

    public function container(): Application
    {
        return $this->container;
    }

    public function scheduler(): LaravelSchedule
    {
        return $this->schedule;
    }

    public function isReady(): bool
    {
        return $this->status === Process::READY;
    }

    public function isRunning(): bool
    {
        return $this->status === Process::STARTED;
    }

    public function isStarted(): bool
    {
        return $this->status !== Process::READY;
    }

    public function isTerminated(): bool
    {
        return $this->status === Process::TERMINATED;
    }

    /**
     * Get the current instance of the Schedule.
     */
    public static function instance(): self
    {
        return static::$instance ??= new static;
    }

    /**
     * Destroy the current instance of the Schedule.
     */
    public static function destroy(): void
    {
        static::$instance = null;
        Application::getInstance()->flush();
    }

    private function prepare(): self
    {
        $this
            ->setCallback()
            ->setPathTasks();

        return $this;
    }

    private function setCallback(): self
    {
        $schedule = Kirby::instance()->option('beebmx.scheduler.schedule');

        if (is_callable($schedule)) {
            $schedule(static::instance());
        }

        return $this;
    }

    private function setPathTasks(): self
    {
        $tasks = Kirby::instance()->option('beebmx.scheduler.tasks');

        if (is_string($tasks) && realpath($tasks) !== false && F::exists($tasks)) {
            require realpath($tasks);
        }

        return $this;
    }

    /**
     * Dynamically handle calls into the schedule instance.
     *
     * @return mixed
     */
    public function __call(string $method, array $parameters)
    {
        if (method_exists(PendingEventAttributes::class, $method)) {
            return $this->schedule->$method(...$parameters);
        }

        throw new BadMethodCallException(sprintf(
            'Method %s::%s does not exist.', static::class, $method
        ));
    }
}
