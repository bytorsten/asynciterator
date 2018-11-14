<?php
namespace AsyncIterator\Tests;

use AsyncIterator\AsyncIterator;
use function AsyncIterator\forAwaitEach;
use PHPUnit\Framework\TestCase;
use function React\Promise\resolve;

class ForAwaitEachTest extends TestCase
{
    public function testForEachAwaitShouldResolveAllPromisesAndTerminate()
    {
        $asyncIterator = new AsyncIterator(['a', resolve('b')]);

        $i = 0;
        forAwaitEach($asyncIterator, function ($value, int $index) use (&$i) {
            static::assertEquals($i, $index);
            static::assertEquals($index === 0 ? 'a' : 'b', $value);

            $i++;
        });

        static::assertEquals(2, $i);
    }
}
