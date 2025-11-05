<?php

arch()->preset()->php();

arch()->preset()->security();

arch()->preset()->strict()
    ->ignoring('usleep');

arch('globals')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();

arch('app')
    ->expect('App\Actions')
    ->toBeInvokable();
