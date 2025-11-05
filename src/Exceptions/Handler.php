<?php

declare(strict_types=1);

namespace Beebmx\KirbScheduler\Exceptions;

use Illuminate\Contracts\Debug\ExceptionHandler;
use Throwable;

final class Handler implements ExceptionHandler
{
    public function shouldReport(Throwable $e): bool
    {
        return false;
    }

    public function report(Throwable $e): void
    {
        throw $e;
    }

    public function render($request, Throwable $e)
    {
        throw $e;
    }

    /**
     * @throws Throwable
     */
    public function renderForConsole($output, Throwable $e): void
    {
        throw $e;
    }
}
