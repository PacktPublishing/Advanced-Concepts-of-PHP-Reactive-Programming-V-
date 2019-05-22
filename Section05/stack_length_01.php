<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';

use Rx\Observable;
use Rx\Scheduler;
use Rx\Observer\CallbackObserver;

Observable::range(1, 10, Scheduler::getImmediate())
    ->doOnNext(function($val) { /* do whatever */ })
    ->startWithArray([12, 15, 17])
    ->skip(1)
    ->map(function($val) {
        return $val * 2;
    })
    ->filter(function($val) {
        return $val % 3 === 0;
    })
    ->doOnNext(function($val) { /* do whatever */ })
    ->takeLast(3)
    ->sum()
    ->doOnNext(function($val) { /* do whatever */ })
    ->subscribe(new CallbackObserver(function() {
        $backtrace = debug_backtrace();
        $len = count($backtrace);

        foreach ($backtrace as $item) {
            $args = count($item['args']);
            $func = $item['function'];
            if (isset($item['file'])) {
                $file = substr($item['file'], strrpos($item['file'], '/') + 1);
                echo "${file}#${item['line']} ${func} ${args} arg/s\n";
            } else {
                echo "${func} ${args} arg/s\n";
            }
        }
        echo "============\n";
        echo "Stack length: ${len}\n";
    }));
