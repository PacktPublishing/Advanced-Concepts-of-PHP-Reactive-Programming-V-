<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';

use Rx\Observable;

$obs1 = Observable::interval(1000)
    ->map(function($i) { return chr(65 + $i); });
$obs2 = Observable::interval(500)
    ->map(function($i) { return $i + 42; });

Observable::interval(200)
    ->zip([$obs1, $obs2])
    ->subscribe(new DebugSubject());
