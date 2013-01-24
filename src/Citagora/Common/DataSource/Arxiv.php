<?php

namespace Citagora\Common\DataSource;

use Citagora\Common\Tool\DocumentFactory;
use Citagora\Common\Entity\Document\Document;
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

    protected function mapFields($sourceRecord, Document $document, DocumentFactory $df)
    {
        $source = $sourceRecord->metadata->arXiv;

        //Basic info
        $document->title           = (string) $source->title;
        $document->doi             = (string) $source->doi;
        $document->abstract        = (string) $source->abstract;
        $document->publicationType = 'Journal Article';

        //Authors
        foreach($source->authors->author as $author) {
            
            $contributor = $df->factory('Contributor');
            $contributor->surname   = $author->keyname;
            $contributor->givenname = $author->forenames;
            $document->addContributor($contributor);
        }



        //Return it
        return $document;     
    }

    // --------------------------------------------------------------

    protected function getUnmappedFields($sourceRecord)
    {
        $source = $sourceRecord->metadata->arXiv;

        $unmappedFields = array();
        $unmappedFields['journal-ref'] = (string) $source->{'journal-ref'};
        $unmappedFields['created']     = (string) $source->created;
        $unmappedFields['updated']     = (string) $source->updated;
        $unmappedFields['categories']  = (string) $source->categories;
        $unmappedFields['comments']    = (string) $source->comments;
        $unmappedFields['report-no']   = (string) $source->{'report-no'};

        return $unmappedFields;      
    }    
}

/* EOF: Arxiv.php */