<?php

require_once '../vendor/autoload.php';
require_once '../files/DebugSubject.php';

use Rx\Observable;
use Rx\Notification\OnNextNotification;

Observable::range(1, 3)
    ->materialize()
    ->subscribe(new DebugSubject());
