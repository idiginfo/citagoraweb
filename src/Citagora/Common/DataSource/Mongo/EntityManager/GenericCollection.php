<?php

namespace Citagora\Common\DataSource\Mongo\EntityManager;

/**
 * Generic Entity Collection
 */
class GenericCollection extends Collection
{
    /**
     * @var string
     */
    private $entityCN;

    // --------------------------------------------------------------

    public function __construct($entityClassName)
    {
        $this->entityCN = $entityClassName;
    }

    // --------------------------------------------------------------

    /**
     * Get entity classname
     */
    public function getEntityClassName()
    {
        return $this->entityCN;
    }
}

/* EOF: GenericCollection.php */