<?php

require_once '../vendor/autoload.php';
require_once '../files/DebugSubject.php';

use Rx\Observable;

Observable::range(1, 3)
    ->map(function($value) {
        return Observable::range(0, $value);
    })
    ->mergeAll()
    ->subscribe(new DebugSubject());
