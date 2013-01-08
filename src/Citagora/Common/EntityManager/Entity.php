<?php

namespace Citagora\Common\EntityManager;

use InvalidArgumentException;

abstract class Entity
{
    // --------------------------------------------------------------

    public function __set($item, $val)
    {
        //Item must exist
        $vars = array_keys($this->toArray(true));
        if ( ! in_array($item, $vars)) {
            throw new InvalidArgumentException("Invalid class property: '$item' in " . get_called_class());
        }
    
        if ($item == 'id') {
            throw new InvalidArgumentException("Cannot set id property.  Reserved for system use.");
        }

        $this->$item = $val;
    }

    // --------------------------------------------------------------

    public function __get($item)
    {
        return $this->$item;
    }

    // --------------------------------------------------------------
    
    /**
     * Magic __tostring() method for serializing
     *
     * Useful for using array functions on entity objects
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
        $vars = array_keys(get_class_vars(get_called_class()));
        return in_array($name, $vars);
    }
    
    // --------------------------------------------------------------
    
    /**
     * Return an array representation of the entity
     *
     * \@TODO: Make this use the Metadata class in Doctrine ODM
     * @return array
     */
    public function toArray($includeId = true)
    {
        $ref = new \ReflectionObject($this);
        $properties = $ref->getProperties();

        $arr = array();
        foreach($properties as $prop) {

            $annots = $prop->getDocComment();
            if (stripos($annots, "@ODM\\")) {

                if (stripos($annots, '@ODM\Id') && ! $includeId) {
                    continue;
                }

                $prop->setAccessible(true);
                $arr[$prop->getName()] = $this->__get($prop->getName());
            }            
        }

        return $arr;
    }
}

/* EOF: Entity.php */