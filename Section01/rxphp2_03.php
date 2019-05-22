<?php

require __DIR__ . "/vendor/autoload.php";

use WyriHaximus\React\AsyncInteropLoop\ReactDriverFactory;
use Interop\Async\Loop;
use Rx\Observable;

Loop::setFactory(ReactDriverFactory::createFactory());

Observable::interval(1000)
    ->take(3)
    ->subscribe(function($value) {
        echo "First: $value\n";
    });

Loop::get()->run();

Observable::interval(1000)
    ->take(3)
    ->subscribe(function($value) {
        echo "Second: $value\n";
    });


