<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/DirectoryIteratorObservable.php';

use Rx\Observable;
use Rx\ObserverInterface;
use Rx\DisposableInterface;

class DirectoryIteratorSharedObservable extends Observable
{
    private $inner;

    public function __construct()
    {
        $args = func_get_args();
        $this->inner = (new DirectoryIteratorObservable(...$args))
            ->publish();
    }

    public function _subscribe(ObserverInterface $observer): DisposableInterface
    {
        return $this->inner->subscribe($observer);
    }

    public function connect()
    {
        return $this->inner->connect();
    }

    public function refCount()
    {
        return $this->inner->refCount();
    }
}