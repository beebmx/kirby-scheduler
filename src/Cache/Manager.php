<?php

namespace Beebmx\KirbScheduler\Cache;

use Illuminate\Cache\CacheManager;
use Illuminate\Container\Container;

class Manager extends CacheManager
{
    public function __construct(Container $app)
    {
        $this->app = $app;
    }
}
