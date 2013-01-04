<?php

namespace Citagora\DataSource\Type;

use Citagora\Tool\DocumentFactory;
use Citagora\Entity\Document\Document;
/**
 * Data Source Abstract
 */
abstract class Base
{    
    /**
     * @var array  Array of Param objects
     */
    private $availableParams = array();

    /**
     * @var array  Array of key/value representing parameters
     */
    private $setParams = array();

    // --------------------------------------------------------------

    /**
     * Return a machine-readable name (alphanum, all lowercase)
     *
     * @return string
     */
    abstract public function getSlug();

    // --------------------------------------------------------------

    /**
     * Return a human-friendly name
     *
     * @return string
     */
    abstract public function getName();

    // --------------------------------------------------------------

    /**
     * return a URL to the source
     *
     * @return string
     */
    abstract public function getUrl();

    // --------------------------------------------------------------

    /**
     * Connect to data source
     *
     * @return boolean  Should return TRUE or throw an exception
     */
    abstract public function connectToSource();

    // --------------------------------------------------------------

    /**
     * Get next record
     *
     * @return mixed|false  Return the record in whatever format or FALSE for no more records
     * @throws NotImplementedException  If this is not available for the datasource
     */
    abstract public function getNextRecord();

    // --------------------------------------------------------------

    /**
     * Get the identifier that the data source uses for the record
     *
     * @param  mixed  The record in whatever format getNextRecord() returned
     * @return string
     * @throws NotImplementedException  If this is not available for the datasource     
     */
    abstract public function getRecordIdentifier($sourceRecord);

    // --------------------------------------------------------------

    /**
     * Get a specific record based off a document object
     *
     * @param Document        $document  The document to use as a reference
     * @return mixed|false  Return the record in whatever format or FALSE if not found
     * @throws NotImplementedException  If this is not available for the datasource
     */
    abstract public function getSpecificRecord(Document $document);

    // --------------------------------------------------------------

    /**
     * Map record to Citagora Document Container Object
     *
     * @param  mixed           $sourceRecord  Whatever format getNextRecord() returned
     * @param  Document        $document      A document to populate
     * @param  DocumentFactory $df            Document Factory for generating document-related entities     
     * @return Document
     */
    abstract public function mapRecord($sourceRecord, Document $document, DocumentFactory $df);

    // --------------------------------------------------------------

    /**
     * Add an paramater (should be done from the constructor in child classes)
     *
     * @param Option $param
     */
    protected function addAvailableParam(Param $param)
    {
        $this->availableParams[] = $param;
        $this->setParams[$param->name] = $param->default;
    }

    // --------------------------------------------------------------

    /**
     * Return array of available options
     *
     * @return array
     */
    public function getAvailableParams()
    {
        return $this->availableParams;
    }

    // --------------------------------------------------------------

    /**
     * Set parameters
     *
     * @param array $params  Key/value of desired settings
     */
    public function setParameters(array $params)
    {
        array_map(array($this, 'setParameter'), $params);
    }

    // --------------------------------------------------------------

    /**
     * Set Parameter
     *
     * @param string $name
     * @param mixed $value
     */
    public function setParameter($name, $value)
    {
        if ( ! in_array($name, array_keys($this->setparams))) {
            throw new \InvalidArgumentException("The parameter {$name} does not exist");
        }

        $this->setParams[$name] = $value;
    }

    // --------------------------------------------------------------

    /**
     * Get the parameters that have been set
     * @return array
     */
    protected function getParameter($name)
    {
        return $this->setParams[$name];
    }
}

/* EOF: SourceAbstract.php */