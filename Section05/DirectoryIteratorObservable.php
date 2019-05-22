<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Rx\Observable;
use Rx\Scheduler;
use Rx\DisposableInterface;
use Rx\ObserverInterface;
use Rx\SchedulerInterface;

class DirectoryIteratorObservable extends Observable
{

    private $iter;
    private $scheduler;
    private $selector;
    private $pattern;

    public function __construct($dir, $pattern = null, $selector = null, $recursive = true, SchedulerInterface $scheduler = null)
    {
        $this->scheduler = $scheduler;
        $this->pattern = $pattern;

        if ($recursive) {
            $dirIter = new RecursiveDirectoryIterator($dir);
            $iter = new RecursiveIteratorIterator($dirIter);
        } else {
            $iter = new DirectoryIterator($dir);
        }

        if ($selector) {
            $this->selector = $selector;
        } else {
            $this->selector = function(SplFileInfo $file) {
                return $file;
            };
        }

        $this->scheduler = $scheduler ?: Scheduler::getImmediate();

        $this->iter = $iter;
    }

    public function _subscribe(ObserverInterface $observer): DisposableInterface
    {
        $this->iter->rewind();

        return $this->scheduler->scheduleRecursive(function ($reschedule) use ($observer) {
            /** @var SplFileInfo $current */
            $current = $this->iter->current();
            $this->iter->next();

            if (!$this->pattern || preg_match($this->pattern, $current)) {
                try {
                    $processed = call_user_func($this->selector, $current);
                    $observer->onNext($processed);
                } catch (\Exception $e) {
                    $observer->onError($e);
                }
            }

            if ($this->iter->valid()) {
                $reschedule();
            } else {
                $observer->onCompleted();
            }
        });
    }

}