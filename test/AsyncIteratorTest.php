<?php
namespace AsyncIterator\Tests;

use function AsyncIterator\createAsyncIterator;
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
}
