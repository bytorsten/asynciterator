<?php
namespace AsyncIterator;


use React\Promise\ExtendedPromiseInterface;

interface ExtendedAsyncIterator extends AsyncIterator
{
    public function throw(\Exception $error): ExtendedPromiseInterface;
    public function return($value = null): ExtendedPromiseInterface;
}
