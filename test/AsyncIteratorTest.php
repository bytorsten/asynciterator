<?php
namespace AsyncIterator\Tests;

use AsyncIterator\AsyncIterator;
use PHPUnit\Framework\TestCase;
use React\Promise\PromiseInterface;
use function React\Promise\resolve;

class AsyncIteratorTest extends TestCase
{

    public function testAsyncIteratorShouldWorkWithoutElements()
    {
        $asyncIterator = new AsyncIterator();

        foreach ($asyncIterator as $item) {
            static::fail();
        }

        static::assertEquals(0, count(iterator_to_array($asyncIterator)));
    }

    public function testAsyncIteratorShouldReturnPromises()
    {
        $asyncIterator = new AsyncIterator(['not a promise', resolve('a promise')]);

        $i = 0;
        foreach ($asyncIterator as $item) {
            static::assertInstanceOf(PromiseInterface::class, $item);
            $i++;
        }

        static::assertEquals(2, $i);
    }
}
