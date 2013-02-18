<?php

namspace Citagora\Common\Model;

class Factory
{
    private $defaultNamespace;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param string $defaultNamespace  Set a default namespace to build objects from
     */
    public function __construct($defaultNamespace = 'Citagora\Common\Model')
    {
        //Get slashes how we want them
        if ($defaultNamespace) {
            $defaultNamespace = "\\" . trim($defaultNamespace, "\\") . "\\";
        }

        $this->defaultNamespace = $defaultNamespace;
    }

    // --------------------------------------------------------------

    /**
     * Build an model object for the given type
     *
     * @param string $which  The classname to instantiate (relative to default namespace or full classname)
     * @return Model
     * @throws InvalidArgumentException  If an invalid classname is sent
     */
    public function build($which)
    {
        if (class_exists($which)) {
            return new $which;
        }
        elseif (class_exists($defaultNamespace . $which)) {
            return new $defaultNamespace . $which;
        }
        else {
            throw InvalidArgumentException("Cannot create {$which}.  Class does not exist!");
        }
    }
}

/* EOF: Factory.php */