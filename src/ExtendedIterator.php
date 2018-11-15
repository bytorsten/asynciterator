<?php
namespace AsyncIterator;

interface ExtendedIterator extends Iterator
{
    public function throw(\Exception $error);
    public function return($value = null): array;
}
