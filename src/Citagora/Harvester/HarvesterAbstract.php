<?php

namespace Citagora\Harvester;
use Citagora\Entity\Document\Document;
use Citagora\EntityCollection\DocumentCollection;
use Citagora\EntityManager\Manager as EntityManager;

use TaskTracker\Tracker;
use RuntimeException;

/**
 * Havest Documents
 */
abstract class HarvesterAbstract
{
    /**
     * @var Citagora\EntityManager\Manager;
     */
    private $em;

    /**
     * @var Citagora\EntityCollection\DocumentCollection
     */
    private $collection;


    // --------------------------------------------------------------

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
        $this->collection = $em->getCollection('Document\Document');
    }

    // --------------------------------------------------------------

    /**
     * @return string
     */
    abstract public function getName();

    // --------------------------------------------------------------

    /**
     * @return string
     */
    abstract public function getDescription();

    // --------------------------------------------------------------

    /**
     * @return array  Keys are option names, values are defaults
     */
    abstract public function getOptions();

    // --------------------------------------------------------------

    /**
     * @return boolean
     */
    abstract protected function connectToSource(array $options);

    // --------------------------------------------------------------

    /**
     * Get document from source (do no processing on it yet)
     *
     * @return mixed
     */
    abstract protected function retrieveNextDocument(array $options);

    // --------------------------------------------------------------

    /**
     * Process the document
     *
     * @param mixed $sourceData
     * @return Citagora\Entity\Document\Document
     */
    abstract protected function mapDocument($sourceData, Document $document);

    // --------------------------------------------------------------

    /**
     * Harvest
     *
     * @param  DocumentCollection  The collection in which to save records
     * @param  array    $options
     * @param  int      $limit    0 or null for no limit
     * @param  Tracker  $tracker  Task Tracker
     * @return int      Number of documents harvested
     */
    public function harvest(array $options, $limit = null, Tracker $tracker = null)
    {
        //Ensure collection is configured
        if ( ! $this->collection) {
            throw new RuntimeException("Cannot harvest without having set entity manager.  Use setEntityManager() method");
        }

        //Total number
        $count = 0;

        //Connect to the source
        $this->connectToSource($options);

        if ($tracker) {
            $tracker->start();
        }

        //While we have documents and we are beneath our limit, keep harvesting
        while($sourceDoc = $this->retrieveNextDocument($options)) {

            //Get out if we have reached our limit
            if ($limit && $count > $limit) {
                break;
            }

            //Build a new empty Citagora document to be populated
            $citagoraDoc = $this->collection->factory();

            //Map it
            $citagoraDoc = $this->mapDocument($sourceDoc, $citagoraDoc);

            //Save it
            $result = $this->saveDocument($citagoraDoc);

            //Increment counter
            $count++;

            //Tick
            if ($tracker) {
                $tracker->tick("Importing from " . $this->getName(), $result);
            }
        }

        if ($tracker) {
            $tracker->finish();
        }

        return $count;
    }

    // --------------------------------------------------------------

    /**
     * Build an entity
     * 
     * @param string
     * @return Entity
     */
    protected function buildEntity($name)
    {
        return $this->em->getCollection($name)->factory();
    }

    // --------------------------------------------------------------

    /**
     * Save the document
     *
     * @param Citagora\Entity\Document\Document
     * @return boolean  TRUE if saved; FALSE if skipped
     */
    private function saveDocument(Document $document)
    {
        //@TODO: Check if document exists already

        $this->collection->save($document);
        return true;
    }
}

/* EOF: HarvesterAbstract.php */