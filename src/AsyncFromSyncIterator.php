<?php
namespace AsyncIterator;

use React\Promise\PromiseInterface;
use function React\Promise\resolve;

class AsyncFromSyncIterator implements AsyncIterator
{
    private $_i;
    private $returned = false;

    public function __construct(Iterator $iterator)
    {
        $this->_i = $iterator;
    }

    public function next(): PromiseInterface
    {
        if ($this->returned) {
            return resolve(['done' => true]);
        }

        $step = $this->_i->next();
        return resolve($step['value'])->then(function ($value) use ($step) {
           return ['value' => $value, 'done' => $step['done']];
        });
    }

    public function throw(\Throwable $error): PromiseInterface
    {
        throw $error;
    }

    public function return(): PromiseInterface
    {
        $this->returned = true;
    }
}
