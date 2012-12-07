<?php

namespace Citagora\Model;

abstract class AbstractValueObject
{
    public function __get($item)
    {
        return $this->$item;
    }

    // -------------------------------------------------------------

    public function __set($item, $val)
    {
        //Item must exist
        $vars = array_keys(get_class_vars(get_called_class()));
        if ( ! in_array($item, $vars)) {
            throw new InvalidArgumentException("Non-existent class property: $item");
        }

        $this->$item = $val;
    }

    // -------------------------------------------------------------

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
}

/* EOF: AbstractValueObject.php */