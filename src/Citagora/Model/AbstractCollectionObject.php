<?php

namespace Citagora\Model;
use Doctrine\Common\Collections\ArrayCollection;

abstract class AbstractCollectionObject extends ArrayCollection
{
    /**
     * Add Item Method used for object type constraint
     *
     * Meant to be overriden by accepting a single parameter,
     * which it doesn't have to do anything with
     *
     * @param object $item
     */
    protected abstract function addItem();

    // --------------------------------------------------------------

    /**
     * Adds/sets an element in the collection at the index / with the specified key.
     *
     * When the collection is a Map this is like put(key,value)/add(key,value).
     * When the collection is a List this is like add(position,value).
     *
     * @param mixed $key
     * @param mixed $value
     */
    public function set($key, $value)
    {
        $this->addItem($value);
        parent::set($key, $value);
    }

    // --------------------------------------------------------------

    /**
     * Adds an element to the collection.
     *
     * @param mixed $value
     * @return boolean Always TRUE.
     */
    public function add($value)
    {
        $this->addItem($value);
        parent::add($value);
    }
}

/* EOF: AbstractCollectionObject.php */