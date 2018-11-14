<?php
namespace AsyncIterator;


use React\Promise\PromiseInterface;

interface AsyncIterator
{
    public function next(): PromiseInterface;
    public function return(): PromiseInterface;
    public function throw(\Throwable $error): PromiseInterface;
}
