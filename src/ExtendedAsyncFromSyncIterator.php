<?php
namespace AsyncIterator;

use React\Promise\ExtendedPromiseInterface;
use function React\Promise\reject;
use function React\Promise\resolve;

class ExtendedAsyncFromSyncIterator implements ExtendedAsyncIterator
{
    private $_i;

    public function __construct(ExtendedIterator $iterator)
    {
        $this->_i = $iterator;
    }

    public function next(): ExtendedPromiseInterface
    {
        try {
            $step = $this->_i->next();
            return resolve($step['value'])->then(function ($value) use ($step) {
                return ['value' => $value, 'done' => $step['done']];
            });
        } catch (\Throwable $error) {
            return reject($error);
        }
    }

    public function return($value = null): ExtendedPromiseInterface
    {
        return resolve($this->_i->return($value));
    }

    public function throw(\Exception $error): ExtendedPromiseInterface
    {
        try {
            $this->_i->throw($error);
            return $this->next();
        } catch (\Exception $error) {
            return reject($error);
        }
    }
}
