<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once './ProcessObservable.php';
require_once '../Chapter 02/DebugSubject.php';

$pid = tempnam(sys_get_temp_dir(), 'pid_proc1');

$observable = new ProcessObservable('php sleep.php proc1 3', $pid);
$disposable = $observable->subscribe(new DebugSubject());
