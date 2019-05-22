<?php

require_once '../vendor/autoload.php';
require_once '../files/DebugSubject.php';

use Rx\Subject\Subject;
use Rx\Observable;
use Rx\Notification\OnNextNotification;

Observable::create(function(\Rx\ObserverInterface $observer) {
        $observer->onNext(1);
        $observer->onNext(2);
        $observer->onError(new Exception());
        $observer->onNext(4);
        $observer->onError(new Exception());
        $observer->onNext(6);
    })
    ->materialize()
    ->subscribe(new DebugSubject());
