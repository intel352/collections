<?php

namespace intel352\Collections;

use CallbackFilterIterator;
use Countable;
use Iterator;

class Filter extends CallbackFilterIterator implements Countable
{

    public function __construct(Iterator $iterator, Callable $p)
    {
        parent::__construct($iterator, $p);
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     */
    public function count()
    {
        $i=0;
        foreach($this as $item) $i++;
        return $i;
    }
}