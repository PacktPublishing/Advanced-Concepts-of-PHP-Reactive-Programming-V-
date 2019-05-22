<?php

require_once '../vendor/autoload.php';
require_once '../files/DebugSubject.php';

use Rx\Observable;
use Rx\Notification;
use Rx\Subject\Subject;
use Rx\Notification\OnErrorNotification;
use Rx\Notification\OnNextNotification;

Observable::range(1, 9)
    ->materialize()
    ->map(function(Notification $notification) {
        $val = null;
        $notification->accept(function($next) use (&$val) {
            $val = $next;
        }, function() { }, function() use (&$val) {
            $val = -1;
        });

        if ($val % 3 == 0) {
            $msg = "It's really broken";
            $e = $val == 6 ? new LogicException($msg) : new \Exception();
            return new OnErrorNotification($e);
        } else {
            return $notification;
        }
    })
    ->subscribe(new DebugSubject());
