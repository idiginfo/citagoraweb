<?php

namespace Citagora\Common\DataSource;

use Citagora\Common\Entity\Document\Document;
use Citagora\Common\Tool\DocumentFactory;
use SimpleXMLElement, DateTime;

/**
 * Nature Publishing Group Datasource
 */
class Nature extends Type\Oaipmh
{
    // --------------------------------------------------------------
    
    public function getSlug()
    {
        return 'nature';
    }

    // --------------------------------------------------------------

    public function getName()
    {
        return 'Nature Publishing Group';
    }

    // --------------------------------------------------------------

    public function getUrl()
    {
        return 'http://nature.com';
    }

    // --------------------------------------------------------------

    public function getOaiPmhUrl()
    {
        return 'http://www.nature.com/oai/request';
    }

    // --------------------------------------------------------------

    public function getOaiPmhMetadataPrefix()
    {
        return 'pam';
    }

    // --------------------------------------------------------------

    public function getRecordIdentifier($sourceRecord)
    {
        //Register namespaces
        $sourceRecord = $this->registerNamespaces($sourceRecord);

        //Get the ID
        return $this->getXpathValue($sourceRecord, '//header/identifier');
    }

    // --------------------------------------------------------------

    protected function mapFields($sourceRecord, Document $document, DocumentFactory $df)
    {
        //Register namespaces
        $sourceRecord = $this->registerNamespaces($sourceRecord);

        //Basic info
        $document->title           = $this->getXpathValue($sourceRecord, '//pam:article//dc:title');
        $document->issn            = $this->getXpathValue($sourceRecord, '//pam:article//prism:issn');
        $document->doi             = $this->getXpathValue($sourceRecord, '//pam:article//prism:doi');
        $document->journal         = $this->getXpathValue($sourceRecord, '//pam:article//prism:publicationName');
        $document->pagination      = $this->getXpathValue($sourceRecord, '//pam:article//prism:startingPage');
        $document->url             = $this->getXpathValue($sourceRecord, '//pam:article//prism:url');
        $document->publicationType = 'Journal Article';

        //Date and Year
        $rawDate = $this->getXpathValue($sourceRecord, '//pam:article//prism:publicationDate');
        if ($rawDate) {
            $document->pubDate         = DateTime::createFromFormat('Y-m-d', $rawDate);
            $document->year            = $document->pubDate->format('Y');
        }

        //Authors
        $authorList = (array) $this->getXpathValue($sourceRecord, '//pam:article//dc:creator');

        foreach($authorList as $author) {
            $contributor = $df->factory('Contributor');
            $contributor->fullname = $author;
            $document->addContributor($contributor);
        }

        //Return it..
        return $document;        
    }

    // --------------------------------------------------------------

    protected function getUnmappedFields($sourceRecord)
    {
        //Register namespaces
        $sourceRecord = $this->registerNamespaces($sourceRecord);
        
        $unmappedFields = array();
        $unmappedFields['eissn']     = $this->getXpathValue($sourceRecord, '//pam:article//prism:eIssn');
        $unmappedFields['volume']    = $this->getXpathValue($sourceRecord, '//pam:article//prism:volume');
        $unmappedFields['number']    = $this->getXpathValue($sourceRecord, '//pam:article//prism:number');
        $unmappedFields['copyright'] = $this->getXpathValue($sourceRecord, '//pam:article//prism:copyright');

        return $unmappedFields;      
    }

    // --------------------------------------------------------------

    /**
     * @param SimpleXMLElement $sourceRecord
     * @return SimpleXMLElement
     */
    private function registerNamespaces(SimpleXMLElement $sourceRecord)
    {
        $sourceRecord->registerXPathNamespace('pam', 'http://prismstandard.org/namespaces/pam/2.0/');
        $sourceRecord->registerXPathNamespace('prism', 'http://prismstandard.org/namespaces/basic/2.0/');
        $sourceRecord->registerXPathNamespace('dc', 'http://purl.org/dc/elements/1.1/');
        $sourceRecord->registerXPathNamespace('xsi', 'http://www.w3.org/2001/XMLSchema-instance');

        return $sourceRecord;
    }
}

/* EOF: Nature.php */