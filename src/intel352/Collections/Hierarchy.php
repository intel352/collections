<?php

namespace intel352\Collections;

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
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     */
    public function hasChildren()
    {
        return !$this->_children->isEmpty();
    }

    /**
     * @return HierarchyCollection
     */
    public function getChildren()
    {
        return $this->_children;
    }
}