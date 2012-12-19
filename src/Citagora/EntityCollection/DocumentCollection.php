<?php

namespace Citagora\EntityCollection;
use Citagora\EntityManager\Collection as EntityCollection;
use Citagora\Entity\Document;

/**
 * Manages users in the database
 */
class DocumentCollection extends EntityCollection
{
    /**
     * Get entity classname (hard-coded)
     */
    public function getEntityClassName()
    {
        return 'Document\Document';
    }
}

/* EOF: DocumentCollection.php */