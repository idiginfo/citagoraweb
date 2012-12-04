<?php

namespace Citagora\Model;
use Doctrine\Common\Collections\ArrayCollection;
use ArrayAccess, IteratorAggregate, Countable;

class AbstractCollectionObject implements ArrayAccess, IteratorAggregate
{
    /**
     * @var Doctrine\Common\Collections\ArrayCollection
     */
    private $collection;

    /**
     * Override default ArrayCollection behavior by enabling typehinting
     */
    public function __construct(array $elements = array())
    {
        $this->collection = new ArrayCollection();

        foreach($elements as $k => $v) {
            $this->set($k, $v);
        }
    }

    // --------------------------------------------------------------

    public function add($element)
    {
        $this->collection->add($element);
    }

    // --------------------------------------------------------------

    public function set($key, $value)
    {
        $this->collection->set($key, $value);
    }
 
    // --------------------------------------------------------------

    public function count()
    {
        return $this->collection->count();
    }

    public function getIterator()
    {
        return $this->collection->getIterator();
    }

    public function offsetExists($offset)
    {
        return $this->collection->offsetExists($offset);
    }

    public function offsetGet($offset)
    {
        return $this->collection->offsetGet($offset);
    }

    public function offsetSet($offset, $value)
    {
        return $this->collection->offsetSet($offset, $value);
    }

    public function offsetUnset($offset)
    {
        return $this->collection->offsetUnset($offset);
    }
 
    // --------------------------------------------------------------

    public function __call($method, $arguments)
    {
        if (is_callable($this, $method)) {
            return call_user_func_array(array($this, $method), $arguments);
        }
        elseif (is_callable($this->collection, $method)) {
            return call_user_func_array(array($this->collection, $method), $arguments);   
        }
        else {
            throw new \RuntimeException("Undefined method '$method'");
        }
    }
}

/* EOF: AbstractCollectionObject.php */