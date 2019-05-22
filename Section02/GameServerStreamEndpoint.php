<?php

require_once '../vendor/autoload.php';
require_once '../files/JSONDecodeOperator.php';
require_once './StreamObservable.php';

use React\Promise\Deferred;
use Rx\Observable\ConnectableObservable;
use Rx\Subject\ReplaySubject;
use React\EventLoop\LoopInterface;
use Rx\SchedulerInterface;

class GameServerStreamEndpoint {

    /** @var StreamObservable */
    private $stream;
    /** @var Deferred */
    private $initDeferred;
    /** @var ConnectableObservable */
    private $status;
    /** @var Rx\SchedulerInterface */
    private $scheduler;

    public function __construct($stream, LoopInterface $loop, SchedulerInterface $scheduler) {
        $this->stream = new StreamObservable($stream, $loop);

        $this->initDeferred = new Deferred();

        $decodedMessage = $this->stream
            ->lift(function() {
                return new JSONDecodeOperator();
            });

        $unsubscribe = $decodedMessage
            ->filter(function($message) {
                return $message['type'] == 'init';
            })
            ->pluck('data')
            ->subscribe(function($data) use (&$unsubscribe) {
                $this->initDeferred->resolve($data['port']);
                $unsubscribe->dispose();
            });

        $this->status = $decodedMessage
            ->filter(function($message) {
                return $message['type'] == 'status';
            })
            ->pluck('data')
           ->do(function($obs) {
                var_dump($obs);
            })
            ->multicast(new ReplaySubject(1, null, $scheduler));

        $this->status->connect();
    }

    public function getStatus() {
        return $this->status
            ->do(function($obs) {
                var_dump('getStatus');
     
       });
    }

    public function onInit() {
        return $this->initDeferred->promise();
    }

    public function close() {
        return $this->stream->close();
    }

}
