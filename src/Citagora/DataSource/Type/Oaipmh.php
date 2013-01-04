<?php

namespace Citagora\DataSource\Type;

use Citagora\Entity\Document\Document;
use Phpoaipmh\Endpoint, SimpleXMLElement;

/**
 * OAI-PMH Data Source Abstract Class
 */
abstract class Oaipmh extends Base
{
    /**
     * @var Phpoaipmh\Endpoint
     */
    private $endpoint;

    /**
     * @var Phpoaipmh\ResponseList
     */
    private $responseListObj;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Phpoaipmh\Endpoint $endpoint
     */
    public function __construct(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;

        $this->addAvailableParam(new Param('from', 'From Date (YYYY-MM-DD)', ''));
        $this->addAvailableParam(new Param('to',   'To Date (YYYY-MM-DD)',   ''));
        $this->addAvailableParam(new Param('set',  'From which set?',        ''));
    }

    // --------------------------------------------------------------

    /**
     * URL of the OAI-PMH URL
     *
     * @return string
     */
    abstract public function getOaiPmhUrl();

    // --------------------------------------------------------------

    /**
     * Metadata prefix to use for data gathering
     *
     * @return string
     */
    abstract public function getOaiPmhMetadataPrefix();

    // --------------------------------------------------------------

    /**
     * Connect to OAI-PMH Endpoint source
     *
     * {@inherit}
     */
    public function connectToSource()
    {
        $params = array();
        if ($this->getParameter('from')) {
            $params['from'] = $this->getParameter('from');
        }
        if ($this->getParameter('to')) {
            $params['to'] = $this->getParameter('to');
        }
        if ($this->getParameter('set')) {
            $params['set'] = $this->getParameter('set');
        }

        $this->endpoint->setUrl($this->getOaiPmhUrl());
        
        $this->responseListObj = $this->endpoint->listRecords(
            $this->getOaiPmhMetadataPrefix(), 
            $params
        );

        return true;
    }

    // -------------------------------------------------------------------------

    /**
     * Get specific record
     *
     * @return SimpleXMLELement|false
     */
    public function getSpecificRecord(Document $document)
    {
        $sourceSlug = $this->getSlug();

        if (isset($document->meta->sources[$sourceSlug])) {
            $id = $document->meta->sources[$sourceSlug];
            return $this->endpoint->getRecord($id, $this->getOaiPmhMetadataPrefix());
        }
        else { //no ID can be used to find the record
            return false;
        }
    }

    // -------------------------------------------------------------------------

    /**
     * @return SimpleXMLElement|false
     */ 
    public function getNextRecord()
    {
        return $this->responseListObj->nextItem();
    }

    // -------------------------------------------------------------------------

    /**
     * Helper method to get a value from an XPath
     *
     * If there a multiple values, they will be returned as an array
     *
     * @param  SimpleXMLElement $record  The record to pull from
     * @param  string           $xpath   The XPATH, relative to the record
     * @return string|array|null         Will return the value or NULL if it doesn't exist
     */
    protected function getXpathValue(SimpleXMLElement $record, $xpath)
    {
        $item = $record->xpath($xpath);

        $vals = array();
        if (count($item) > 0) {

            foreach($item as $v) {
                $vals[] = trim((string) $v);
            }

            return (count($vals) == 1) ? $vals[0] : $vals;
        }
        else {
            $vals = null;
        }

        return $vals;
    }    
}

/* EOF: OaipmhAbstract.php */