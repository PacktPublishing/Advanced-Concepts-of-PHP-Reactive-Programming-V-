<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';
require_once 'ThrottleTimeOperator.php';

use Rx\Observable;
use React\EventLoop\StreamSelectLoop;
use Rx\Scheduler\EventLoopScheduler;

$lastTimestamp = 0;

Observable::interval(150)
    ->lift(function()  {
        return new ThrottleTimeOperator(1000);
    })
    ->subscribe(new DebugSubject());
