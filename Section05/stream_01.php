<?php

require_once '../vendor/autoload.php';
require_once '../Chapter 02/DebugSubject.php';

use Rx\Observable;
use Rx\Scheduler\EventLoopScheduler;
use React\EventLoop\StreamSelectLoop;

class MyStream
{

    private $outputBuffer = [];
    private $storage;
    /** @var \React\EventLoop\LoopInterface $loop */
    private $loop;

    public $context;

    public function stream_open($path, $mode, $options)
    {
        $options = stream_context_get_options($this->context);
        $this->storage = fopen('php://memory', 'w');

        $this->loop = $options['my']['loop'];


        $this->loop->addTimer(3, function() {
            var_dump('fire');
            fwrite($this->storage, 'Hello, World!');
        });

        return true;
    }

    public function stream_read($count)
    {
        return "Hello, World! 123";
    }

    public function stream_cast()
    {
        var_dump('cast');
        return $this->storage;
    }
}

$loop = new StreamSelectLoop();
$scheduler = new EventLoopScheduler($loop);

stream_wrapper_register('my', MyStream::class);
stream_context_set_default([
    'my' => [
        'loop' => $loop,
    ],
]);

$resource = fopen("my://whatever_i_need", '');

$loop->addReadStream($resource, function($data) {
    var_dump($data);
});

$loop->run();

