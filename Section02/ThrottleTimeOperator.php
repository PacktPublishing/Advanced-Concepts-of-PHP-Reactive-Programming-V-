<?php

require_once '../vendor/autoload.php';
require_once '../files/DebugSubject.php';

use Rx\Operator\OperatorInterface;
use Rx\ObservableInterface;
use Rx\ObserverInterface;
use Rx\DisposableInterface;
use Rx\Observable;

class ThrottleTimeOperator implements OperatorInterface
{

    private $duration;
    private $lastTimestamp = 0;

    public function __construct($duration)
    {
        $this->duration = $duration;
    }

    public function __invoke(ObservableInterface $observable, ObserverInterface $observer): DisposableInterface
    {
        /** @var Observable $observable */
        $disposable = $observable->filter(function() use ($observer) {
            $now = microtime(true) * 1000;
            if ($this->lastTimestamp + $this->duration <= $now) {
                $this->lastTimestamp = $now;
                return true;
            } else {
                return false;
            }
        })->subscribe($observer);

        return $disposable;
    }
}
