<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use Rx\Observable;

$lastTimestamp = 0;

Observable::interval(150)
    ->filter(function() use (&$lastTimestamp) {
        if ($lastTimestamp + 1 <= microtime(true)) {
            $lastTimestamp = microtime(true);
            return true;
        } else {
            return false;
        }
    })
    ->subscribe(new DebugSubject());
