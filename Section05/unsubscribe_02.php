<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';

use Rx\Observable;
use Rx\Observer\CallbackObserver;
use Rx\Subject\Subject;

$subject = new Subject();

$subscription = Observable::range(1, 10)
    ->takeUntil($subject)
    ->subscribe(new CallbackObserver(
        function($val) use ($subject) {
            echo "$val\n";
            if ($val === 3) {
                $subject->onNext(null);
            }
        },
        null, // no error handler
        function() {
            echo "completed!\n";
        })
    );
