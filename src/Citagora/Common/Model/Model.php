<?php

namespace Citagora\Common\Model;

use Exception, ReflectionClass;

/**
 * Model class:
 *   - Allows attributes to be serialized as JSON
 *   - Allows controlled access to attributes via __GET and __SET
 *   - Allows direct use of attributes in TWIG templates
 *   - DB and ORM implementation-independent.
 *
 * Use the annotation '@Attribute' to define which properties are attributes
 */
abstract class Model
{
    // --------------------------------------------------------------

    /**
     * Set an attribute
     *
     * @param string $item  Which attribute to set
     * @param mixed  $val   Value
     */
    public function __set($item, $val)
    {
        //Item must be an attribute
        if ( ! in_array($item, self::getAttributeNames())) {
            throw new Exception(sprintf("Cannot set protected or private property %s::%s", get_called_class(), $item));
        }

        $this->$item = $val;
    }

    // --------------------------------------------------------------

    /**
     * Get an attribute
     *
     * @param string $item  Which attribute
     * @return mixed
     */
    public function __get($item)
    {
        if ( ! in_array($item, self::getAttributeNames())) {
            throw new Exception(sprintf("Cannot access protected or private property %s::%s", get_called_class(), $item));
        }

        return $this->$item;
    }

    // --------------------------------------------------------------

    /**
     * Magic method for checking if attributes are set
     *
     * Useful for integrating entities with Twig
     *
     * @param string $name
     * @return boolean
     */
    public function __isset($item)
    {
        if ( ! in_array($item, self::getAttributeNames())) {
            throw new Exception(sprintf("Cannot access protected or private property %s::%s", get_called_class(), $item));
        }

        return isset($this->$item);
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
     * Return an array representation of the attributes
     *
     * @return array  Keys are property names; values are property values
     */
    public function toArray()
    {
        return $this->getAttributes();
    }

    // --------------------------------------------------------------

    /**
     * Get attributes
     *
     * Returns an array representation of the attributes
     *
     * @return array
     */
    public function getAttributes()
    {
        $arr = array();
        foreach(self::getAttributeNames() as $prop) {
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
    public static function getAttributeNames()
    {
        $ref = new ReflectionClass(get_called_class());
        $properties = $ref->getProperties();

        $arr = array();
        foreach($properties as $prop) {

            if (preg_match("/\*\s+?@Attribute/i", $prop->getDocComment())) {
                $prop->setAccessible(true);
                $arr[] = $prop->getName();
            }
        }

        return $arr;
    }

}

/* EOF: Model */