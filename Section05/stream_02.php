<?php

require_once '../vendor/autoload.php';

use Rx\Observable;
use Rx\Scheduler\EventLoopScheduler;
use React\EventLoop\StreamSelectLoop;


$storage = fopen('php://memory', 'w');

$loop = new StreamSelectLoop();

$loop->addReadStream(fopen('php://temp', 'r'), function($storage) {
    $str = fgets($storage, 1024);
    if ($str) {
        var_dump($str);
    }
});

$loop->addTimer(1, function() use ($storage) {
    fwrite($storage, 'Hello, World!');
});

$loop->run();
