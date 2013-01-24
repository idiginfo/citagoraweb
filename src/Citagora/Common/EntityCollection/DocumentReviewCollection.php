<?php

namespace Citagora\Common\EntityCollection;

use Citagora\Common\EntityManager\Collection as EntityCollection;
use Citagora\Common\Entity\Document\Document;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Citagora\Common\Events;

/**
 * Manages users in the database
 */
class DocumentReviewCollection extends EntityCollection
{
    /**
     * Get entity classname (hard-coded)
     */
    public function getEntityClassName()
    {
        return 'Document\Review';
    }    

    // --------------------------------------------------------------

    /**
     * Find a user review for a document
     *
     * Returns a review object or null if the review doesn't exist
     *
     * @return Citagora\Common\Entity\Document\Review|null
     */
    public function getUserReview(Document $document, User $user)
    {
        //Cannot get review object for non-persisted user
        if ( ! $user->id) {
            return null;
        }

        $query = $this->getQueryBuilder();

    }
}

/* EOF: DocumentReviewCollection.php */