<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../files/DebugSubject.php';

use Rx\Observable;
use Rx\Scheduler;

$scheduler = Scheduler::getDefault();

$source = Observable::of(42)
    ->combineLatest([
        Observable::interval(175, $scheduler)->take(3)->startWith(null),
        Observable::interval(250, $scheduler)->take(3)->startWith(null),
        Observable::interval(100, $scheduler)->take(5)->startWith(null),
    ])
    ->subscribe(new DebugSubject());
