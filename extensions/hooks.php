<?php

use Illuminate\Container\Container;
use Kirby\Cms\App;

return [
    'system.loadPlugins:after' => function () {
        // if (App::instance()->option('beebmx.kirby-blade.bootstrap', false)) {
        //     $container = Application::getInstance();
        //     Container::setInstance($container);
        // }
    },
];
