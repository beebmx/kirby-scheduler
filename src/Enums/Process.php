<?php

declare(strict_types=1);

namespace Beebmx\KirbScheduler\Enums;

enum Process: string
{
    case READY = 'ready';
    case STARTED = 'started';
    case TERMINATED = 'terminated';
}
