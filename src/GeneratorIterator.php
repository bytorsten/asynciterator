<?php
namespace AsyncIterator;

class GeneratorIterator implements ExtendedIterator
{
    public $_g;
    protected $skipNext = true;

    public function __construct(\Generator $g)
    {
        $this->_g = $g;
    }

    private function advance()
    {
        if ($this->skipNext) {
            $this->skipNext = false;
        } else {
            try {
                $this->_g->next();
            } catch (ReturnException $e) {}
        }
    }

    public function next(): array
    {
        $this->advance();
        if (!$this->_g->valid()) {
            return [ 'value' => null, 'done' => true ];
        }

        return [ 'value' => $this->_g->current(), 'done' => false ];
    }

    public function return($value = null): array
    {
        try {
            $this->_g->throw(new ReturnException());
        } catch (ReturnException $e) {}

        if ($this->_g->valid()) {
            $this->skipNext = true;
            return $this->next();
        }

        return ['value' => $value, 'done' => true ];
    }

    public function throw(\Exception $error)
    {
        $this->skipNext = true;
        $this->_g->throw($error);
    }
}
