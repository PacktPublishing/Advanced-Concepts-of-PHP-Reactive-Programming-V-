<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';

use Rx\Observable;

$obs1 = Observable::range(1, 7);
$obs2 = Observable::fromArray(['a', 'b']);
$obs3 = Observable::range(42, 5);

$object = new stdClass();

$obs1->zip([$obs2, $object])
    ->subscribe(new DebugSubject());
