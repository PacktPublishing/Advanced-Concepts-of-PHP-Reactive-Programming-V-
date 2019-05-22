<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';

use Rx\Observable;

$observable = Observable::defer(function() {
        printf("Observable::defer\n");
        return Observable::range(1, 3);
    })
    ->share();

$observable->subscribe(new DebugSubject('1'));
$observable->subscribe(new DebugSubject('2'));
