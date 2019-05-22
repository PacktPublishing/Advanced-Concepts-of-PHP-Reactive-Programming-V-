<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';

use Rx\Observable;
use Rx\Observer\CallbackObserver;
use Rx\Scheduler\EventLoopScheduler;
use React\EventLoop\StreamSelectLoop;

$subscription = Observable::range(1, 10)
    ->subscribe(new CallbackObserver(
        function($val) use (&$subscription) {
            echo "$val\n";
            if ($val === 3) {
                $subscription->dispose();
            }
        },
        null, // no error handler
        function() {
            echo "completed!\n";
        })
    );
