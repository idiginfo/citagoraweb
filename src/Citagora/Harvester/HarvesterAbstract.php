<?php

namespace Citagora\Harvester;
use Citagora\Entity\Document\Document;
use Citagora\EntityManager\Manager as EntityManager;

abstract class HarvesterAbstract
{
    /**
     * @var Citagora\EntityManager\Manager
     */
    private $em;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Citagora\EntityManager\Manager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
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
     * @param  array $options
     * @param  int   $limit    0 or null for no limit
     * @return int   Number of documents harvested
     */
    public function harvest(array $options, $limit = null)
    {
        //Keep track
        $count = 0;

        //Connect to the source
        $this->connectToSource($options);

        //While we have documents and we are beneath our limit, keep harvesting
        while($sourceDoc = $this->retrieveNextDocument($options)) {

            //Get out if we have reached our limit
            if ($limit && $count > $limit) {
                break;
            }

            //Build a new empty Citagora document to be populated
            $citagoraDoc = $this->buildEntity('Document\Document');

            //Map it
            $citagoraDoc = $this->mapDocument($sourceDoc, $citagoraDoc);

            //Save it
            $this->saveDocument($citagoraDoc);

            //Increment counter
            $count++;
        }

        return $count;
    }

    // --------------------------------------------------------------

    /**
     * Build a new Entity of a specified type
     *
     * @param string
     * @return Citagora\EntityManager\Entity;
     */
    protected function buildEntity($entityName)
    {
        return $this->em->getCollection($entityName)->factory();
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
        $docCollection = $this->em->getCollection('Document\Document');

        //@TODO: Check if document exists already

        $docCollection->save($document);
        return true;
    }
}

/* EOF: HarvesterAbstract.php */