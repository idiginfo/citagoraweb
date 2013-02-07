<?php

namespace Citagora\Common\Model;

use ReflectionObject, InvalidArgumentException;

/**
 * Model class:
 *   - Allows protected attributes to be serialized as JSON
 *   - Allows controlled access to attributes via __GET and __SET
 *   - Allows direct use of protected attributes in TWIG templates
 *   - DB and ORM implementation-independent.
 *
 * Make a property protected for it to be accessible via __GET and __SET,
 * and serialization.  Make it private for it to be truely private
 * 
 */
abstract class Model
{
    // --------------------------------------------------------------

    public function __set($item, $val)
    {
        //Item must exist
        if ( ! in_array($item, $this->getAttributes())) {
            throw new InvalidArgumentException("Cannot set property for model: '{$item}' in " . get_called_class());
        }

        $this->$item = $val;
    }

    // --------------------------------------------------------------

    public function __get($item)
    {
        if ($this->__isset($item)) {
            return $this->$item;
        }
    }

    // --------------------------------------------------------------

    /**
     * Magic __tostring() method for serializing
     *
     * Useful for using array functions on entity objects
     *
     * @return string
     */
    public function __tostring()
    {
        return json_encode($this->toArray());
    }

    // --------------------------------------------------------------

    /**
     * Magic method for checking if properties are set
     *
     * Useful for integrating entities with Twig
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($name)
    {
        return in_array($name, $this->getAttributes());
    }

    // --------------------------------------------------------------

    /**
     * Return an array representation of the entity
     *
     * @return array  Keys are property names; values are property values
     */
    public function toArray()
    {
        $arr = array();
        foreach($this->getAttributes() as $prop) {
            $arr[$prop] = $this->__get($prop);
        }

        return $arr;
    }

    // --------------------------------------------------------------

    /**
     * Get protected properties for the object
     *
     * @return array  Names of properties that are attributes
     */
    public function getAttributes()
    {
        $ref = new ReflectionObject($this);

        $arr = array();
        foreach($ref->getProperties() as $prop) {
            $prop->setAccessible(true);

            if ($prop->isProtected()) {
                $arr[] = $prop->getname();
            }
        }

        return $arr;
    }

}

/* EOF: Model */