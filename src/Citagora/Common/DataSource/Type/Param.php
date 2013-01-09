<?php

namespace Citagora\Common\DataSource\Type;

class Param
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var string
     */
    private $default;

    public function __construct($name, $description, $default = null)
    {
        $this->name        = $name;
        $this->description = $description;
        $this->deafult     = $default;
    }

    // --------------------------------------------------------------

    public function __get($which)
    {
        return $this->$which;
    }

    // --------------------------------------------------------------

    public function __isset($which)
    {
        return isset($this->$which);
    }
}

/* EOF: Option.php */