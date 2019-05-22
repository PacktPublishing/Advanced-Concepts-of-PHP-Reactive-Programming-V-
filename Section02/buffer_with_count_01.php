<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use Rx\Observable;

$lastTimestamp = 0;

Observable::interval(500)
    ->bufferWithCount(4)
    ->subscribe(new DebugSubject());
