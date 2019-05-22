<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once '../files/DebugSubject.php';

use React\EventLoop\StreamSelectLoop;
use Rx\Scheduler\EventLoopScheduler;
use Rx\Observable;
use Rx\Scheduler;

$range = [1];

$loop = new StreamSelectLoop();
$scheduler = new EventLoopScheduler($loop);

$newServerTrigger = Observable::interval(1000, $scheduler);
$statusUpdate = Observable::interval(600, $scheduler)->publish();
$statusUpdate->connect(); // Make it hot Observable

$newServerTrigger
    ->map(function() use (&$range, $statusUpdate) {
        $range[] = count($range) + 1;
        $observables = array_map(function($val) {
            return Observable::just($val, Scheduler::getImmediate());
        }, $range);

        return $statusUpdate
            ->combineLatest($observables, function() {
                $values = func_get_args();
                array_shift($values);
                return $values;
            });
    })
    ->switchLatest()
    ->take(8)
    ->doOnCompleted(function() use ($loop) {
        $loop->stop();
    })
    ->subscribe(new DebugSubject());

$loop->run();
