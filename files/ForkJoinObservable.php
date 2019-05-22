<?php

use Rx\Observable;
use Rx\ObserverInterface;
use Rx\Scheduler;
use Rx\DisposableInterface;
use Rx\Disposable\CompositeDisposable;
use Rx\Scheduler\ImmediateScheduler;

class ForkJoinObservable extends Observable
{

    /**
     * @var Observable[]
     */
    private $observables;

    private $lastValues = [];

    private $completed = [];

    public function __construct($observables)
    {
        $this->observables = $observables;
    }

    public function _subscribe(ObserverInterface $observer): DisposableInterface
    {
        $disp = new CompositeDisposable();

        foreach ($this->observables as $i => $obs) {
            $innerDisp = $obs->subscribe(function ($v) use ($i) {
                    $this->lastValues[$i] = $v;
                }, function ($e) use ($observer) {
                    $observer->onError($e);
                }, function () use ($i, $observer) {
                    $this->completed[$i] = true;

                    $completed = count($this->completed);
                    if ($completed == count($this->observables)) {
                        $observer->onNext($this->lastValues);
                        $observer->onCompleted();
                    }
                }
            );
            $disp->add($innerDisp);
        }

        return $disp;
    }

}
