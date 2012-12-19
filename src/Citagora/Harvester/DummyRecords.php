<?php

namespace Citagora\Harvester;
use Citagora\Entity\Document\Document;

/**
 * Import Dummy Records
 */
class DummyRecords extends HarvesterAbstract
{
    /**
     * @var array
     */
    private $records;

    /**
     * @var int
     */
    private $pointer = 0;

    // --------------------------------------------------------------

    /**
     * @return string
     */
    public function getName()
    {
        return 'dummyrecs';
    }  

    // --------------------------------------------------------------

    /**
     * @return string
     */
    public function getDescription()
    {
        return "Dummy Records from JSON file";
    }

    // --------------------------------------------------------------

    /**
     * @return array
     */
    public function getOptions()
    {
        $options = array();

        $options['filepath'] = array(
            'default'    => null,
            'description' => 'Full filepath to dummy records JSON file'
        );

        return $options;
    }

    // --------------------------------------------------------------

    protected function connectToSource(array $options)
    {
        $filename = $options['filepath'];

        if ( ! is_readable($filename)) {
            throw new InvalidOptionException("Cannot read from dummy records file: " . $filename);
        }

        $rawData  = file_get_contents($filename);
        $jsonData = json_decode($rawData);

        if ( ! $jsonData) {
            throw new HarvesterException("Error parsing dummy records from path: " . $filename);
        }

        $this->records = $jsonData;
        return true;
    }

    // --------------------------------------------------------------

    protected function retrieveNextDocument(array $options)    
    {
        if (isset($this->records[$this->pointer])) {
            $doc = $this->records[$this->pointer];
            $this->pointer++;
            return $doc;
        }
        else {
            return null;
        }
    }

    // --------------------------------------------------------------

    /**
     * Process the document
     *
     * @param mixed $sourceData
     * @param  Citagora\Entity\Document\Document $document
     * @return Citagora\Entity\Document\Document
     */
    protected function mapDocument($sourceData, Document $document)
    {
        //Source to Document
        $mappings = array(
            'pmid'     => 'pmid',
            'year'     => 'year',
            'journal'  => 'journal',
            'isbn'     => 'isbn',
            'pages'    => 'pagination',
            'abstract' => 'abstract',
            'url'      => 'url',
            'doi'      => 'doi'
        );

        //Title is required
        $document->title = $sourceData->title;

        //Basic Mappings
        foreach($mappings as $source => $dest) {
            if (isset($sourceData->$source)) {
                $document->$dest = $sourceData->$source;
            }
        }

        //Authors        
        foreach($sourceData->authorList as $author) {
            $contributor = $this->buildEntity('Document\Contributor');
            $contributor->fullname = $author;
            $contributor->type     = 'Author';
            $document->addContributor($contributor);
        }

        //Keywords
        if (isset($sourceData->keywords)) {
            foreach($sourceData->keywords as $kw) {
                $document->addKeyword($kw);
            }
        }

        //Send it back
        return $document;
    }  
}

/* EOF: DummyRecords.php */