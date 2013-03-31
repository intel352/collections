<?php

namespace intel352\Collections;

use RecursiveIterator;

class Hierarchy
{
    /** @var HierarchyCollection */
    private $_children;

    public function __construct()
    {
        $this->_children = new HierarchyCollection;
    }

    /**
     * Returns if an iterator can be created for the current entry.
     * @link http://php.net/manual/en/recursiveiterator.haschildren.php
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     */
    public function hasChildren()
    {
        return !$this->_children->isEmpty();
    }

    /**
     * Returns an iterator for the current entry.
     * @link http://php.net/manual/en/recursiveiterator.getchildren.php
     * @return HierarchyCollection
     */
    public function getChildren()
    {
        return $this->_children;
    }
}