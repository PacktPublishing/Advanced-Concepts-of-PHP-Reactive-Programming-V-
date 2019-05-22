<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';
require_once 'remote_api_interface.php';

use Rx\Observable;
use Rx\ObserverInterface;
use Rx\Disposable\CallbackDisposable;

class RemoteServiceAPI implements RemoteAPI {
    public function connect($connectionDetails) { }
    public function fetch($path, $callback) { }
    public function close() { }
}

Observable::create(function(ObserverInterface $observer) {
    $producer = new RemoteServiceAPI();
    $producer->connect('...');

    $producer->fetch('whatever', function($result) use ($observer) {
        $observer->onNext($result);
    });

    return new CallbackDisposable(function() use ($producer) {
        $producer->close();
    });
});

