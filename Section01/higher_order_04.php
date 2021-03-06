<?php

require_once '../vendor/autoload.php';
require_once '../files/DebugSubject.php';

use Rx\Observable;
use React\EventLoop\StreamSelectLoop;
use Rx\Scheduler\EventLoopScheduler;

Observable::interval(1000)
    ->take(3)
    ->map(function($value) {
        return Observable::interval(600)
            ->take(3)
            ->map(function($counter) use ($value) {
                return sprintf('#%d: %d', $value, $counter);
            });
    })
    ->mergeAll()
    ->subscribe(new DebugSubject());
