<?php
namespace AsyncIterator;

use React\Promise\ExtendedPromiseInterface;
use function React\Promise\resolve;

class AsyncFromSyncIterator implements AsyncIterator
{
    private $_i;
    private $returned = false;

    public function __construct(Iterator $iterator)
    {
        $this->_i = $iterator;
    }

    public function next(): ExtendedPromiseInterface
    {
        if ($this->returned) {
            return resolve(['value' => null, 'done' => true]);
        }

        $step = $this->_i->next();
        return resolve($step['value'])->then(function ($value) use ($step) {
           return ['value' => $value, 'done' => $step['done']];
        });
    }
}
