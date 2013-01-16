<?php

namespace Citagora\Common\EntityCollection;

use Citagora\Common\EntityManager\Collection as EntityCollection;
use Citagora\Common\Entity\Document\Document;
use Doctrine\Common\Collections\ArrayCollection;


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

    // --------------------------------------------------------------

    public function getLatestDocuments($limit = 10)
    {
        $qb = $this->getQueryBuilder();
        $qb->sort('meta.modified', 'desc');
        $qb->limit($limit);

        return $qb->getQuery()->execute();
    }

    // --------------------------------------------------------------

    /**
     * Save a record, but try to avoid duplicates
     *
     * {@inherit}
     */
    public function save(Document $record, $flush = true, $clear = false)
    {
        //Check if an existing document exists with this record
        $existing = $this->checkDocumentExists($record);

        //Merge into existing
        if ($existing) {
            $record = $this->mergeDocuments($record, $existing);
        }

        //Save it
        return parent::save($record, $flush, $clear);
    }

    // --------------------------------------------------------------

    /**
     * Check if an existing document exists based on a new document
     *
     * If an existing document exists, then this method will return
     * it.  If not, then false is returned
     *
     * @param Document $document
     * @return Document|false
     */
    public function checkDocumentExists(Document $document)
    {
        //Unique fields
        $uniqueField = array('doi', 'isbn', 'url', 'pmid');

        //Build a query to search for documents in the db with unique fields
        $qb = $this->getQueryBuilder();
        foreach ($uniqueField as $uf) {
            if ($document->$uf) {
                $qb->addOr($qb->expr()->field($uf)->equals($document->$uf));
            }
        }

        //Run the query
        return $qb->getQuery()->getSingleResult() ?: false;
    }

    // --------------------------------------------------------------

    /**
     * @param Document $source  The document that contains information to be added to the target
     * @param Document $target  The document that will be kept
     * @return Document         Returns the target document
     */
    private function mergeDocuments(Document $source, Document $target)
    {
        //Merge arrays method
        $mergeArrays = function($prop, $val) use ($target) {
            foreach ($val as $item) {
                if ( ! in_array($item, $target->$prop)) {
                    $target->$addKeyword($item);
                }
            }
        };

        //Merge Lists method
        //@TODO: Debug this; it is adding contributors to target even if they are duplicates
        $mergeLists = function($prop, $method, ArrayCollection $sourceList) use ($target) {
            $targetList = $target->$prop;
            foreach($sourceList as $item) {
                if ( ! $targetList->contains($item)) {
                    $target->$method($item);
                }
            }
        };

        //Merge meta
        $mergeMeta = function($meta) use ($target) {
            foreach($meta->sources as $source => $id) {
                if ( ! in_array($target->meta->sources)) {
                    $target->meta->addSource($source, $id);
                }
            }
        };

        //Merge social
        $mergeSocial = function($social) use ($target) {
            foreach($social->toArray() as $prop => $val) {
                if ($val && ! $target->social->$prop) {
                    $target->social->$prop = $val;
                }
            }
        };

        //Convert source to an array without the ID field...
        foreach($source->toArray(false) as $prop => $val) {

            switch($prop) {
                case 'normalizedTitle':
                    //do nothing
                break;
                case 'meta':
                    $mergeMeta($val);
                break;
                case 'contributors':
                    $mergeLists($prop, 'addContributor', $val);
                break;
                case 'citations':
                    $mergeLists($prop, 'addCitation', $val);                
                break;
                case 'ratings':
                    $mergeLists($prop, 'addRating', $val);
                break;
                case 'keywords':
                    $mergeArrays($prop, $val);
                break;
                default:
                    if ($source->$prop && ! $target->$prop) {
                        $target->$prop = $source->$prop;
                    }
                break;
            }
        }

        return $target;
    }
}

/* EOF: DocumentCollection.php */