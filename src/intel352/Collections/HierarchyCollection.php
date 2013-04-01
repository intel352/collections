<?php

namespace intel352\Collections;

use InvalidArgumentException;
use RecursiveIterator;

class HierarchyCollection extends Collection implements RecursiveIterator
{

    /**
     * Returns if an iterator can be created for the current entry.
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     */
    public function hasChildren()
    {
        $current = $this->current();
        if ($current instanceof Hierarchy) {
            return $current->hasChildren();
        }
        return false;
    }

    /**
     * Returns an iterator for the current entry.
     * @return HierarchyCollection An iterator for the current entry.
     * @throws InvalidArgumentException
     */
    public function getChildren()
    {
        $current = $this->current();
        if ($current instanceof Hierarchy) {
            return $current->getChildren();
        }
        throw new InvalidArgumentException;
    }

}