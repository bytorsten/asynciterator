<?php
namespace AsyncIterator\Tests;

use function AsyncIterator\createAsyncIterator;
use function AsyncIterator\createIterator;
use AsyncIterator\ExtendedAsyncFromSyncIterator;
use PHPUnit\Framework\TestCase;

class AsyncIteratorTest extends TestCase
{

    public function testAsyncIteratorShouldWorkWithoutElements()
    {
        $asyncIterator = createAsyncIterator([]);

        $info = null;
        $asyncIterator->next()->then(function (array $i) use (&$info) {
            $info = $i;
        });

        static::assertEquals(true, $info['done']);
        static::assertEquals(null, $info['value']);
    }

    public function testWorksWithGenerators()
    {
        $source = function () {
            yield 1;
            yield 2;
            yield 3;
        };


        $asyncIterator = createAsyncIterator($source());
        static::assertInstanceOf(ExtendedAsyncFromSyncIterator::class, $asyncIterator);

        $asyncIterator->next()->done(function ($result) {
            static::assertEquals(['value' => 1, 'done' => false], $result);
        });

        $asyncIterator->next()->done(function ($result) {
            static::assertEquals(['value' => 2, 'done' => false], $result);
        });

        $asyncIterator->next()->done(function ($result) {
            static::assertEquals(['value' => 3, 'done' => false], $result);
        });

        $asyncIterator->next()->done(function ($result) {
            static::assertEquals(['value' => null, 'done' => true], $result);
        });
    }
}
