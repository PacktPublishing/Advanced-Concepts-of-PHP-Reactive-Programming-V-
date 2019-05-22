<?php

require_once '../vendor/autoload.php';
require_once '../files/DebugSubject.php';

use Rx\Observable;

Observable::interval(1000)
    ->take(3)
    ->map(function($value) {
        return Observable::interval(600)
            ->take(3)
            ->map(function($counter) use ($value) {
                return sprintf('#%d: %d', $value, $counter);
            });
    })
    ->concatAll()
    ->subscribe(new DebugSubject());
