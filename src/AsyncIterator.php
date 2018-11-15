<?php
namespace AsyncIterator;


use React\Promise\ExtendedPromiseInterface;

interface AsyncIterator
{
    public function next(): ExtendedPromiseInterface;
}
