<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';

use Rx\Observable;
use Rx\Subject\ReplaySubject;

$subject = new ReplaySubject(3);

Observable::range(1, 8)
    ->subscribe($subject);

$subject->subscribe(new DebugSubject());
