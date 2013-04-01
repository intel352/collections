<?php

namespace intel352\Collections;

use ArrayAccess;
use Closure;
use Countable;
use InvalidArgumentException;
use Iterator;
use Judy;
use RecursiveIterator;
use Traversable;

class HierarchyCollection implements Iterator, Countable, ArrayAccess
{
    /** @var Judy|Iterator|ArrayAccess|Countable */
    private $_children;

    public function __construct()
    {
        $this->_children = new Judy(Judy::STRING_TO_MIXED);
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     */
    public function current()
    {
        return $this->_children->current();
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     */
    public function next()
    {
        $this->_children->next();
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     */
    public function key()
    {
        return $this->_children->key();
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     */
    public function valid()
    {
        return $this->_children->valid();
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     */
    public function rewind()
    {
        $this->_children->rewind();
    }

    /**
     * Returns if an iterator can be created for the current entry.
     * @return bool true if the current entry can be iterated over, otherwise returns false.
     */
    public function hasChildren()
    {
        $current = $this->_children->current();
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
        $current = $this->_children->current();
        if ($current instanceof Hierarchy) {
            return $current->getChildren();
        }
        throw new InvalidArgumentException;
    }

    /**
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     */
    public function count()
    {
        return $this->_children->count();
    }

    /**
     * Whether a offset exists
     * @link http://php.net/manual/en/arrayaccess.offsetexists.php
     * @param mixed $offset An offset to check for.
     * @return boolean true on success or false on failure.
     * The return value will be casted to boolean if non-boolean was returned.
     */
    public function offsetExists($offset)
    {
        return $this->_children->offsetExists($offset);
    }

    /**
     * Offset to retrieve
     * @link http://php.net/manual/en/arrayaccess.offsetget.php
     * @param mixed $offset The offset to retrieve.
     * @return mixed Can return all value types.
     */
    public function offsetGet($offset)
    {
        return $this->_children->offsetGet($offset);
    }

    /**
     * Offset to set
     * @link http://php.net/manual/en/arrayaccess.offsetset.php
     * @param mixed $offset The offset to assign the value to.
     * @param mixed $value The value to set.
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        $this->_children->offsetSet($offset, $value);
    }

    /**
     * Offset to unset
     * @link http://php.net/manual/en/arrayaccess.offsetunset.php
     * @param mixed $offset The offset to unset.
     * @return void
     */
    public function offsetUnset($offset)
    {
        $this->_children->offsetUnset($offset);
    }

    /**
     * @return bool true if empty, false otherwise
     */
    public function isEmpty()
    {
        return $this->_children->count()===0;
    }

    /**
     * Adds an element at the end of the collection.
     *
     * @param mixed $element The element to add.
     *
     * @return boolean Always TRUE.
     */
    function add($element)
    {
        $this->_children[] = $element;
        return true;
    }

    /**
     * Clears the collection, removing all elements.
     *
     * @return void
     */
    function clear()
    {
        return $this->_children->free();
    }

    /**
     * Checks whether an element is contained in the collection.
     * This is an O(n) operation, where n is the size of the collection.
     *
     * @param mixed $element The element to search for.
     *
     * @return boolean TRUE if the collection contains the element, FALSE otherwise.
     */
    function contains($element)
    {
        foreach($this->_children as $e) {
            if ($e===$element) return true;
        }
        return false;
    }

    /**
     * Removes the element at the specified index from the collection.
     *
     * @param string|integer $key The kex/index of the element to remove.
     *
     * @return mixed The removed element or NULL, if the collection did not contain the element.
     */
    function remove($key)
    {
        if (!$this->_children->offsetExists($key))
            return null;
        $element = $this->_children->offsetGet($key);
        $this->_children->offsetUnset($key);
        return $element;
    }

    /**
     * Removes the specified element from the collection, if it is found.
     *
     * @param mixed $element The element to remove.
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    function removeElement($element)
    {
        foreach($this->_children as $k=>$e) {
            if ($e === $element) {
                $this->_children->offsetUnset($k);
                return true;
            }
        }
        return false;
    }

    /**
     * Checks whether the collection contains an element with the specified key/index.
     *
     * @param string|integer $key The key/index to check for.
     *
     * @return boolean TRUE if the collection contains an element with the specified key/index,
     *                 FALSE otherwise.
     */
    function containsKey($key)
    {
        return $this->_children->offsetExists($key);
    }

    /**
     * Gets the element at the specified key/index.
     *
     * @param string|integer $key The key/index of the element to retrieve.
     *
     * @return mixed
     */
    function get($key)
    {
        return $this->_children->offsetGet($key);
    }

    /**
     * Gets all keys/indices of the collection.
     *
     * @return array The keys/indices of the collection, in the order of the corresponding
     *               elements in the collection.
     */
    function getKeys()
    {
        return array_keys(iterator_to_array($this->_children));
    }

    /**
     * Gets all values of the collection.
     *
     * @return array The values of all elements in the collection, in the order they
     *               appear in the collection.
     */
    function getValues()
    {
        return iterator_to_array($this->_children, false);
    }

    /**
     * Sets an element in the collection at the specified key/index.
     *
     * @param string|integer $key   The key/index of the element to set.
     * @param mixed $value The element to set.
     *
     * @return void
     */
    function set($key, $value)
    {
        $this->_children->offsetSet($key, $value);
    }

    /**
     * Gets a native PHP array representation of the collection.
     *
     * @return array
     */
    function toArray()
    {
        return iterator_to_array($this->_children);
    }

    /**
     * Sets the internal iterator to the first element in the collection and returns this element.
     *
     * @return mixed
     */
    function first()
    {
        return $this->_children->offsetGet($this->_children->first());
    }

    /**
     * Sets the internal iterator to the last element in the collection and returns this element.
     *
     * @return mixed
     */
    function last()
    {
        return $this->_children->offsetGet($this->_children->last());
    }

    /**
     * Tests for the existence of an element that satisfies the given predicate.
     *
     * @param Closure $p The predicate.
     *
     * @return boolean TRUE if the predicate is TRUE for at least one element, FALSE otherwise.
     */
    function exists(Closure $p)
    {
        foreach ($this->_children as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Returns all the elements of this collection that satisfy the predicate p.
     * The order of the elements is preserved.
     *
     * @param Closure $p The predicate used for filtering.
     *
     * @return HierarchyCollection A collection with the results of the filter operation.
     */
    function filter(Closure $p)
    {
        return new Filter($this, $p);
    }

    /**
     * Applies the given predicate p to all elements of this collection,
     * returning true, if the predicate yields true for all elements.
     *
     * @param Closure $p The predicate.
     *
     * @return boolean TRUE, if the predicate yields TRUE for all elements, FALSE otherwise.
     */
    function forAll(Closure $p)
    {
        $filter = new Filter($this, $p);
        return $filter->count()===$this->count();
    }

    /**
     * Applies the given function to each element in the collection and returns
     * a new collection with the elements returned by the function.
     *
     * @param Closure $p
     *
     * @return HierarchyCollection
     */
    function map(Closure $p)
    {
        $cloneCollection = new self();
        iterator_apply($this->_children, function()use($p, $cloneCollection){
                $cloneCollection->offsetSet($this->_children->key(), $p($this->_children->current(), $this->_children->key()));
                return true;
            });
        return $cloneCollection;
    }

    /**
     * Partitions this collection in two collections according to a predicate.
     * Keys are preserved in the resulting collections.
     *
     * @param Closure $p The predicate on which to partition.
     *
     * @return array An array with two elements. The first element contains the collection
     *               of elements where the predicate returned TRUE, the second element
     *               contains the collection of elements where the predicate returned FALSE.
     */
    function partition(Closure $p)
    {
        $trueColl = new self();
        $falseColl = new self();
        iterator_apply($this->_children, function()use($p, $trueColl, $falseColl){
                $key = $this->_children->key();
                $current = $this->_children->current();
                if ($p($current, $key)) {
                    $trueColl->set($key, $current);
                } else {
                    $falseColl->set($key, $current);
                }
                return true;
            });
        return array($trueColl, $falseColl);
    }

    /**
     * Gets the index/key of a given element. The comparison of two elements is strict,
     * that means not only the value but also the type must match.
     * For objects this means reference equality.
     *
     * @param mixed $element The element to search for.
     *
     * @return int|string|bool The key/index of the element or FALSE if the element was not found.
     */
    function indexOf($element)
    {
        foreach ($this->_children as $key => $value) {
            if ($element===$value) {
                return $key;
            }
        }
        return false;
    }

    /**
     * Extracts a slice of $length elements starting at position $offset from the Collection.
     *
     * If $length is null it returns all elements from $offset to the end of the Collection.
     * Keys have to be preserved by this method. Calling this method will only return the
     * selected slice and NOT change the elements contained in the collection slice is called on.
     *
     * @param int $offset The offset to start from.
     * @param int|null $length The maximum number of elements to return, or null for no limit.
     *
     * @return HierarchyCollection
     */
    function slice($offset, $length = null)
    {
        $collection = new self();
        $i = 0;
        do{
            $collection->offsetSet($offset, $this->_children->offsetGet($offset));
            $i++;
            if ($length!==null && $i>=$length) break;
        } while($offset = $this->_children->next());
        return $collection;
    }

}