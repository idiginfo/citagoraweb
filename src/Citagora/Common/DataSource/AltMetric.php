<?php

namespace Citagora\Common\DataSource;

use Citagora\Common\Tool\DocumentFactory;
use Citagora\Common\Entity\Document\Document;

/**
 * AltMetric Data Source
 */
class AltMetric extends Type\Base
{
    public function getSlug()
    {
        return 'altmetric';
    }

    // --------------------------------------------------------------

    public function getName()
    {
        return 'AltMetric API';
    }

    // --------------------------------------------------------------

    public function getUrl()
    {
        return 'http://altmetric.com/';
    }

    // --------------------------------------------------------------

    public function connectToSource()
    {
        //IMPLEMENT THIS
    }

    // --------------------------------------------------------------

    public function getNextRecord()
    {
        throw new Type\NotImplementedException("AltMetric does not support iterating over its collection");
    }

    // --------------------------------------------------------------

    public function getRecordIdentifier($sourceRecord)
    {
        //IMPLEMENT THIS
    }

    // --------------------------------------------------------------

    public function getSpecificRecord(Document $document)
    {
        //IMPLEMENT THIS
    }

    // --------------------------------------------------------------

    public function mapRecord($sourceRecord, Document $document, DocumentFactory $df)
    {
        //IMPLEMENT THIS
    }
}

/* EOF: AltMetric.php */