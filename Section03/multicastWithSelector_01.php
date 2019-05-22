<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';

use Rx\Observable;
use Rx\Observable\ConnectableObservable;
use Rx\Subject\ReplaySubject;
use Rx\Subject\Subject;

$source = Observable::range(1, 3)
    ->multicastWithSelector(function() {
        return new Subject();
    }, function(ConnectableObservable $connectable) {
        return $connectable->concat(Observable::just('concat'));
    });

$source->subscribe(new DebugSubject());
$source->subscribe(new DebugSubject());
