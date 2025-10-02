<?php

arch('globals')
    ->expect(['dd', 'dump', 'ray'])
    ->not->toBeUsed();

arch('app')
    ->expect('App\Actions')
    ->toBeInvokable();
