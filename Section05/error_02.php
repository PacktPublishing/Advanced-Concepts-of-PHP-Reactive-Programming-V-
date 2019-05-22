<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';

use Rx\Observable;
use Rx\Observer\CallbackObserver;

Observable::range(1, 5)
    ->filter(function($val) {
        if ($val === 3) {
            throw new \Exception("It's broken");
        }
    })
    ->subscribe(new CallbackObserver());
