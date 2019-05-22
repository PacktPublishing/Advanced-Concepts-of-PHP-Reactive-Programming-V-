<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';

use Rx\Observable;
use Rx\Subject\AsyncSubject;

$subject = new AsyncSubject();
$subject->subscribe(new DebugSubject());

Observable::range(1, 8)
    ->subscribe($subject);

