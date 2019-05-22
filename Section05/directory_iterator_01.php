<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';
require_once __DIR__ . '/DirectoryIteratorObservable.php';

$dir = __DIR__ . '/../testdir';
(new DirectoryIteratorObservable($dir, '/.+\.php$/'))
    ->subscribe(function(SplFileInfo $file) {
        echo "$file\n";
    });
