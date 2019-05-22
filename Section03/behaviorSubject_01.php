<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';

use Rx\Subject\BehaviorSubject;

$subject = new BehaviorSubject(42);
$subject->subscribe(new DebugSubject());

