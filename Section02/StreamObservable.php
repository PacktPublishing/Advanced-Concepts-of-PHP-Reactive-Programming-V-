<?php

use Rx\Observable;
use Rx\Subject\Subject;
use Rx\ObserverInterface;
use Rx\DisposableInterface;
use React\EventLoop\LoopInterface;

class StreamObservable extends Observable {
    protected $stream;
    protected $subject;
    protected $loop;

    public function __construct($stream, LoopInterface $loop)
    {
        $this->stream = $stream;
        $this->loop = $loop;
        $this->subject = new Subject();

        $this->loop->addReadStream($this->stream, function ($stream) {
            $data = trim(fgets($stream));
            $this->subject->onNext($data);
        });
    }

    public function _subscribe(ObserverInterface $observer): DisposableInterface
    {
        return $this->subject->subscribe($observer);
    }

    public function send($type, $data)
    {
        $message = [
            'type' => $type,
            'data' => $data,
        ];
        fwrite($this->stream, json_encode($message) . "\n");
    }

    public function close() {
        $this->loop->removeReadStream($this->stream);
        fclose($this->stream);
        $this->subject->onCompleted();
    }

}
