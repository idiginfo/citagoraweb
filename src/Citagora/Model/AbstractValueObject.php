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
        $this->$item = $val;
    }
}

/* EOF: AbstractValueObject.php */