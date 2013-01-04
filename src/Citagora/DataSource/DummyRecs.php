<?php

namespace Citagora\DataSource;

use Type\NotImplementedException;
use RuntimeException;
use Citagora\Tool\DocumentFactory;
use Citagora\Entity\Document\Document;

class DummyRecs extends Type\Base
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

    public function getSlug()
    {
        return 'dummy';
    }

    // --------------------------------------------------------------

    public function getName()
    {
        return 'Dummy Records';
    }

    // --------------------------------------------------------------

    public function getUrl()
    {
        return 'http://citagora.com/dev.php';
    }

    // --------------------------------------------------------------

    public function connectToSource()
    {
        $filename = $options['filepath'];

        if ( ! is_readable($filename)) {
            throw new RuntimeException("Cannot read from dummy records file: " . $filename);
        }

        $rawData  = file_get_contents($filename);
        $jsonData = json_decode($rawData);

        if ( ! $jsonData) {
            throw new RuntimeException("Error parsing dummy records from path: " . $filename);
        }

        $this->records = $jsonData;
        return true;        
    }

    // --------------------------------------------------------------

    public function getNextRecord()
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

    public function getRecordIdentifier($sourceRecord)
    {
        return $sourceRecord->id;
    }

    // --------------------------------------------------------------

    public function getSpecificRecord(Document $document)
    {
        if (isset($document->meta->sources['dummy'])) {
            $id = $document->meta->sources['dummy'];

            foreach($this->records as $rec) {
                if ($rec->id == $id) {
                    return $rec;
                }
            }

        }

        //If made it here
        return false;
    }

    // --------------------------------------------------------------

    public function mapRecord($sourceRecord, Document $document, DocumentFactory $df)
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
        $document->title = $sourceRecord->title;

        //Basic Mappings
        foreach($mappings as $source => $dest) {
            if (isset($sourceRecord->$source)) {
                $document->$dest = $sourceRecord->$source;
            }
        }

        //Authors        
        foreach($sourceRecord->authorList as $author) {
            $contributor = $this->buildEntity('Document\Contributor');
            $contributor->fullname = $author;
            $contributor->type     = 'Author';
            $document->addContributor($contributor);
        }

        //Keywords
        if (isset($sourceRecord->keywords)) {
            foreach($sourceRecord->keywords as $kw) {
                $document->addKeyword($kw);
            }
        }

        //Send it back
        return $document;
    }    
}

/* EOF: DummyRecs.php */