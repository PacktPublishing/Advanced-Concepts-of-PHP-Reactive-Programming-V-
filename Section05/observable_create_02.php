<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../files/DebugSubject.php';
require_once 'remote_api_interface.php';

use Rx\Observable;
use Rx\Observer\CallbackObserver;
use Rx\ObserverInterface;
use Rx\Disposable\CallbackDisposable;

class RemoteServiceAPI implements RemoteAPI {
    public function connect($connectionDetails) { }
    public function fetch($path, $callback) { }
    public function close() { }
}

$producer = new RemoteServiceAPI();
$producer->connect('...');

Observable::create(function(ObserverInterface $observer) use ($producer) {
    $producer->fetch('whatever', function($result) use ($observer) {
        $observer->onNext($result);
    });
});
// somewhere later...
   $producer->close();

