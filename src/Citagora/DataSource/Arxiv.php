<?php

namespace Citagora\DataSource;

use Citagora\Tool\DocumentFactory;
use Citagora\Entity\Document\Document;
use SimpleXMLElement, DateTime;

/**
 * Arxiv Datasource
 */
class Arxiv extends Type\Oaipmh
{
    // --------------------------------------------------------------
    
    public function getSlug()
    {
        return 'arxiv';
    }

    // --------------------------------------------------------------

    public function getName()
    {
        return 'Arxiv.org';
    }

    // --------------------------------------------------------------

    public function getUrl()
    {
        return 'http://arxiv.org';
    }

    // --------------------------------------------------------------

    public function getOaiPmhUrl()
    {
        return 'http://export.arxiv.org/oai2';
    }

    // --------------------------------------------------------------

    public function getOaiPmhMetadataPrefix()
    {
        return 'arXiv';
    }

    // --------------------------------------------------------------

    public function getRecordIdentifier($sourceRecord)
    {
        //Get the ID
        return (string) $sourceRecord->header->identifier;
    }

    // --------------------------------------------------------------

    public function mapRecord($sourceRecord, Document $document, DocumentFactory $df)
    {
        $source = $sourceRecord->metadata->arXiv;

        //Basic info
        $document->title           = (string) $source->title;
        $document->doi             = (string) $source->doi;
        $document->abstract        = (string) $source->abstract;
        $document->publicationType = 'Journal Article';

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