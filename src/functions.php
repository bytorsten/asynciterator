<?php
namespace AsyncIterator;

use React\Promise\Promise;
use function React\Promise\resolve;

function createIterator($collection): ?Iterator {
    if ($collection !== null) {
        if ($collection instanceof Iterator) {
            return $collection;
        }

        if (is_array($collection)) {
            return new ArrayLikeIterator($collection);
        }
    }

    return null;
}

function createAsyncIterator($source): ?AsyncIterator {
    if ($source !== null) {
        if ($source instanceof AsyncIterator) {
            return $source;
        }

        $iterator = createIterator($source);
        if ($iterator) {
            return new AsyncFromSyncIterator($iterator);
        }
    }
}

function forAwaitEach(AsyncIterator $source, callable $callback)
{
    $asyncIterator = createAsyncIterator($source);
    if ($asyncIterator) {
        $i = 0;
        return new Promise(static function (callable $resolve, callable $reject) use ($asyncIterator, $callback, $source, &$i): void {
            $next = function () use ($asyncIterator, $callback, $source, &$i, &$next, $reject, $resolve) {
                $asyncIterator->next()->then(function ($step) use ($callback, $source, &$i, $next, $reject, $resolve) {
                    if (!$step['done']) {
                        resolve($callback($step['value'], $i++, $source))->then($next)->otherwise($reject);
                    } else {
                        $resolve();
                    }

                    return null;
                }, $reject);
            };
            $next();
        });
    }
}
