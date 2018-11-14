<?php
namespace AsyncIterator;

class ArrayLikeIterator implements Iterator
{
    private $_o;
    private $_i;

    public function __construct(?array $obj)
    {
        $this->_o = $obj;
        $this->_i = 0;
    }

    public function next(): array
    {
        if ($this->_o === null || $this->_i >= count($this->_o)) {
            $this->_o = null;
            return [ 'value' => null, 'done' => true ];
        }

        return [ 'value' => $this->_o[$this->_i++], 'done' => false];
    }
}
