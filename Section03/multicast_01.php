<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';

use Rx\Observable;
use Rx\Subject\Subject;

$observable = Observable::defer(function() {
        printf("Observable::defer\n");
        return Observable::range(1, 3);
    })
    ->multicast(new Subject());

$observable->subscribe(new DebugSubject('1'));
$observable->subscribe(new DebugSubject('2'));
$observable->connect();
