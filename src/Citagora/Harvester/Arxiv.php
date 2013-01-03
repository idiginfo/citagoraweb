<?php

namespace Citagora\Harvester;
use Citagora\Entity\Document\Document;
use Citagora\EntityManager\Manager as EntityManager;

use Phpoaipmh\Endpoint;
use Phpoaipmh\Http\RequestException,
    Phpoaipmh\OaipmhRequestException;

/**
 * Import Dummy Records
 */
class Arxiv extends HarvesterAbstract
{
    const ARXIV_URL       = 'http://export.arxiv.org/oai2';
    const METADATA_PREFIX = 'arXiv';

    // --------------------------------------------------------------

    /**
     * @var Phpoaipmh\Endpoint
     */
    private $endpoint;

    /**
     * @var Phpaoipmh\ResponseList
     */
    private $responseListObj;

    // --------------------------------------------------------------

    public function __construct(Endpoint $endpoint)
    {
        $this->endpoint = $endpoint;
    }

    // --------------------------------------------------------------

    /**
     * @return string
     */
    public function getName()
    {
        return 'arxiv';
    }  

    // --------------------------------------------------------------

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Arxiv.org OAI PMH Harvester";
    }

    // --------------------------------------------------------------

    /**
     * @return array
     */
    public function getOptions()
    {
        return array();
    }

    // --------------------------------------------------------------

    protected function connectToSource(array $options)
    {
        try {
            $this->endpoint->setUrl(self::ARXIV_URL);
            $this->responseListObj = $this->endpoint->listRecords(self::METADATA_PREFIX);
        } catch (OaipmhRequestException $e) {
            return false;
        } catch (RequestException $e) {
            return false;
        }
    }

    // --------------------------------------------------------------

    protected function retrieveNextDocument(array $options)    
    {
        return $this->responseListObj->nextItem();
    }

    // --------------------------------------------------------------

    /**
     * Process the document
     *
     * @param  SimpleXMLElement $sourceData
     * @param  Citagora\Entity\Document\Document $document
     * @return Citagora\Entity\Document\Document
     */
    protected function mapDocument($sourceData, Document $document)
    {
        $source = $sourceData->metadata->arXiv;

        //Basic info
        $document->title    = (string) $source->title;
        $document->doi      = (string) $source->doi;
        $document->abstract = $source->abstract;

        //Authors
        foreach($source->authors->author as $author) {
            
            $contributor = $this->buildEntity('Document\Contributor');
            $contributor->surname   = $author->keyname;
            $contributor->givenname = $author->forenames;
            $document->addContributor($contributor);
        }

        return $document;
    }  
}
/* EOF: Arxiv.php */