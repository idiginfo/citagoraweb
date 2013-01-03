<?php

namespace Citagora\Amender;

use Citagora\Entity\Document\Document;
use Guzzle\Http\Client;

class Altmetric extends AmenderAbstract
{
    /**
     * @var string
     */
    private $apiKey;

    /**
     * @var Guzzle\Http\Client
     */
    private $guzzle;

    /**
     * @var string
     */
    private $apiUrl = 'http://api.altmetric.com/v1/';

    // --------------------------------------------------------------

    /**
     * @param string $apiKey
     */
    public function __construct(Client $guzzle, $apiKey)
    {
        $this->apiKey = $apiKey;
        $this->guzzle = $guzzle;

        //Set the API URL in the client
        $this->guzzle->setBaseUrl($apiUrl);
    }

    // --------------------------------------------------------------

    /**
     * Test connection to altmetric
     *
     * @return boolean
     */
    public function test()
    {
        //Do a request to Altmetric on a known DOI.

        //Good response?  Return true
    }

    // --------------------------------------------------------------

    /**
     * Amend a Document with AltMetrics, if they are available
     *
     * @param Document $document
     * @return Document
     */
    public function amend(Document $document)
    {
        //Determine if we can lookup this document (PMID or DOI?)

        //If so, do a request to Altmetric

        //Good response?  Then, parse the information
    }
}

/* EOF: AltMetrics.php */