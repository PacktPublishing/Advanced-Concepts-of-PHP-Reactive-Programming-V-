<?php

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/AbstractRxThread.php';

use Rx\ObservableInterface;
use Rx\ObserverInterface;
use Rx\DisposableInterface;
use Rx\Scheduler;
use Rx\SchedulerInterface;
use Rx\Operator\OperatorInterface;
use Rx\Observer\CallbackObserver;
use Rx\Disposable\BinaryDisposable;

class ThreadPoolOperator implements OperatorInterface
{

    private $pool;
    private $scheduler;

    public function __construct($numThreads = 4, $workerClass = Worker::class, $workerArgs = [], $scheduler = null)
    {
        $this->pool = new Pool($numThreads, $workerClass, $workerArgs);
        $this->scheduler = $scheduler ?: Scheduler::getDefault();
    }

    public function __invoke(ObservableInterface $observable, ObserverInterface $observer): DisposableInterface
    {
        $callbackObserver = new CallbackObserver(function(AbstractRxThread $task) {
                /** @var AbstractRxThread $task */
                $this->pool->submit($task);
            },
            [$observer, 'onError'],
            [$observer, 'onCompleted']
        );

        $dis1 = $this->scheduler->schedulePeriodic(function() use ($observer) {
            $this->pool->collect(function(AbstractRxThread $task) use ($observer) {
                /** @var AbstractRxThread $task */
                if ($task->isDone()) {
                    $observer->onNext($task->getResult());
                    return true;
                } else {
                    return false;
                }
            });
        }, 0, 10);

        $dis2 = $observable->subscribe($callbackObserver);
        $disposable = new BinaryDisposable($dis1, $dis2);

        return $disposable;
    }

}