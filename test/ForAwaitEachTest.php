<?php
namespace AsyncIterator\Tests;

use PHPUnit\Framework\TestCase;
use function AsyncIterator\createAsyncIterator;
use function AsyncIterator\forAwaitEach;
use function React\Promise\resolve;

class ForAwaitEachTest extends TestCase
{
    public function testForEachAwaitShouldResolveAllPromisesAndTerminate()
    {
        $asyncIterator = createAsyncIterator(['a', resolve('b')]);

        $i = 0;
        forAwaitEach($asyncIterator, function ($value, int $index) use (&$i) {
            static::assertEquals($i, $index);
            static::assertEquals($index === 0 ? 'a' : 'b', $value);

            $i++;
        });

        static::assertEquals(2, $i);
    }

    public function testForEachAwaitShouldReturnOnReturn()
    {
        $asyncIterator = createAsyncIterator(['a', 'b', 'c', 'd', 'e']);

        $values = [];
        forAwaitEach($asyncIterator, function ($value) use ($asyncIterator, &$values) {
            $values[] = $value;
            if ($value === 'c') {
                $asyncIterator->return();
            }
        });

        static::assertEquals(['a', 'b', 'c'], $values);
    }

    public function testForEachAwaitShouldThrowOnThrow()
    {
        $asyncIterator = createAsyncIterator(['a', 'b', 'c', 'd', 'e']);

        $values = [];
        $result = forAwaitEach($asyncIterator, function ($value) use ($asyncIterator, &$values) {
            $values[] = $value;
            if ($value === 'c') {
                return $asyncIterator->throw(new \Exception('oh oh'));
            }
        });

        $result->done(function () {
            static::fail('should not resolve');
        }, function (\Exception $exception) {
            static::assertEquals($exception->getMessage(), 'oh oh');
        });

        static::assertEquals(['a', 'b', 'c'], $values);
    }

}
