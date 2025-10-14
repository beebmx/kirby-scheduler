<?php

use Kirby\Cms\App;

if (! function_exists('join_paths')) {
    function join_paths($basePath, ...$paths): string
    {
        foreach ($paths as $index => $path) {
            if (empty($path) && $path !== '0') {
                unset($paths[$index]);
            } else {
                $paths[$index] = DIRECTORY_SEPARATOR.ltrim($path, DIRECTORY_SEPARATOR);
            }
        }

        return $basePath.implode('', $paths);
    }
}

if (! function_exists('public_path')) {
    function public_path($path = ''): string
    {
        return join_paths(App::instance()->roots()->index(), $path);
    }
}

if (! function_exists('base_path')) {
    function base_path($path = ''): string
    {
        return join_paths(App::instance()->roots()->base() ?? App::instance()->roots()->index(), $path);
    }
}

if (! function_exists('app_path')) {
    function app_path($path = ''): string
    {
        return join_paths(base_path(App::instance()->option('beebmx.kirby-blade.app_path', 'app')), $path);
    }
}
