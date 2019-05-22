<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Scheduler;
use Rx\Observable;
use Rx\ObserverInterface;
use Rx\SchedulerInterface;
use Rx\DisposableInterface;
use Rx\Disposable\CompositeDisposable;
use Rx\Disposable\CallbackDisposable;
use Rx\Observer\AutoDetachObserver;
use Symfony\Component\Process\Process;

class ProcessObservable extends Observable {

    private $cmd;
    private $pidFile;
    private $scheduler;

    public function __construct($cmd, $pidFile = null, SchedulerInterface $scheduler = null) {
        $this->cmd = $cmd;
        $this->pidFile = $pidFile;
        $this->scheduler = $scheduler;
    }

    public function _subscribe(ObserverInterface $observer): DisposableInterface {
        $process = new Process($this->cmd);
        $process->start();

        $pid = $process->getPid();
        if ($this->pidFile) {
            file_put_contents($this->pidFile, $pid);
        }

        $disposable = new CompositeDisposable();
        $autoObs = new AutoDetachObserver($observer);
        $autoObs->setDisposable($disposable);

        $scheduler = $this->scheduler ?: Scheduler::getDefault();

        $cancelDisp = $scheduler->schedulePeriodic(function() use ($autoObs, $process, $pid, &$cancelDisp) {
            if ($process->isRunning()) {
                $output = $process->getIncrementalOutput();
                if ($output) {
                    $autoObs->onNext($output);
                }
            } elseif ($process->getExitCode() === 0) {
                $output = $process->getIncrementalOutput();
                if ($output) {
                    $autoObs->onNext($output);
                }
                $autoObs->onCompleted();
            } else {
                $e = new Exception($process->getExitCodeText());
                $autoObs->onError($e);
            }
        }, 0, 200);

        $disposable->add($cancelDisp);
        $disposable->add(new CallbackDisposable(function() use ($process) {
            $process->stop(1, SIGTERM);
            if ($this->pidFile) {
                unlink($this->pidFile);
            }
        }));

        return $disposable;
    }
}