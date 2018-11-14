<?php
namespace AsyncIterator;


use React\Promise\PromiseInterface;
use function React\Promise\resolve;

class AsyncIterator implements \Iterator
{
    private $position = 0;
    private $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function current(): PromiseInterface
    {
        return resolve($this->data[$this->position]);
    }

    public function next(): void
    {
        $this->position += 1;
    }

    public function key(): int
    {
        return $this->position;
    }

    public function valid()
    {
        return isset($this->data[$this->position]);
    }

    public function rewind()
    {
        $this->position = 0;
    }
}
