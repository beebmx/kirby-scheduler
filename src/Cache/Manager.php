<?php

declare(strict_types=1);

namespace Beebmx\KirbScheduler\Cache;

use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;

final class Manager extends CacheManager
{
    public function __construct(Container $app)
    {
        $this->app = $app;
    }
}
