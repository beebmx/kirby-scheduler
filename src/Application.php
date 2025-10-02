<?php

declare(strict_types=1);

namespace Beebmx\KirbScheduler;

use Illuminate\Container\Container;
use RuntimeException;

class Application extends Container
{
    /**
     * The application namespace.
     */
    protected ?string $namespace = null;

    public function isDownForMaintenance(): bool
    {
        return false;
    }

    public function environment(...$environments): bool|string
    {
        if (empty($environments)) {
            return 'kirby';
        }

        return in_array(
            'kirby',
            is_array($environments[0]) ? $environments[0] : $environments
        );
    }

    public function runningUnitTests(): bool
    {
        return true;
    }

    public function getNamespace(): string
    {
        if (! is_null($this->namespace)) {
            return $this->namespace;
        }

        $composer = json_decode(file_get_contents(base_path('composer.json')), true);

        foreach ((array) data_get($composer, 'autoload.psr-4') as $namespace => $path) {
            foreach ((array) $path as $pathChoice) {
                if (realpath(app_path()) === realpath(base_path($pathChoice))) {
                    return $this->namespace = $namespace;
                }
            }
        }

        throw new RuntimeException('Unable to detect application namespace.');
    }
}
