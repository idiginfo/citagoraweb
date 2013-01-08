<?php

namespace Citagora\Common\Tool;

use Citagora\Common\EntityManager\Manager as EntityManager;
use InvalidArgumentException;

/**
 * Shortcut Class for insantiating documents and their related entities
 */
class DocumentFactory
{
    /**
     * @var Citagora\EntityManager\Manager
     */
    private $em;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Citagora\EntityManager\Manager
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    // --------------------------------------------------------------

    /**
     * Create a new object
     *
     * @return Citagora\EntityManager\Entity
     */
    public function factory($className)
    {
        //No Meta Allowed
        if ($className == 'Meta') {
            throw new InvalidArgumentException("There is no good reason to instantiate meta class on its own");
        }

        //Get one
        $className = "Document\\" . $className;
        $coll = $this->em->getCollection($className);
        return $coll->factory();
    }    
}

/* EOF: DocumentFactory.php */