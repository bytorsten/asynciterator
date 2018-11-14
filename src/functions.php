<?php
namespace AsyncIterator;

use React\Promise\Promise;
use function React\Promise\resolve;

function forAwaitEach(AsyncIterator $source, callable $callback)
{
    $asyncIterator = $source;
    return new Promise(function (callable $resolve, callable $reject) use ($asyncIterator, $callback) {
        $i = 0;
        $next = function () use ($asyncIterator, $resolve, $reject, $callback, &$next, &$i) {
            if ($asyncIterator->valid() === false) {
                $resolve();
            }

            $asyncIterator
                ->current()
                ->then(function ($value) use ($asyncIterator, $callback, $next, $resolve, $reject, &$i) {
                    if ($asyncIterator->valid() === false) {
                        return $resolve();
                    }

                    return resolve($callback($value, $i))
                        ->done(function () use ($asyncIterator, $next, &$i) {
                            $i++;
                            $asyncIterator->next();
                            $next();
                        }, $resolve);
                }, $reject);
        };

        $next();
    });
}
