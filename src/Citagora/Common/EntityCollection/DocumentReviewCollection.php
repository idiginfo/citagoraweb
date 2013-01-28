<?php

namespace Citagora\Common\EntityCollection;

use Citagora\Common\EntityManager\Collection as EntityCollection;
use Citagora\Common\Entity\Document\Document;
use Citagora\Common\Entity\User;

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
     * Factory for Reviews
     */
    public function factory(Document $document, User $user = null)
    {
        return parent::factory(array($document, $user));
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

        return $this->findOneBy(array(
            'user.id' => $user->id,
            'document.id' => $document->id
        ));
    }
}

/* EOF: DocumentReviewCollection.php */