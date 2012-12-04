<?php

namespace Citagora\Service;

use Citagora\Model\DocumentView\Document,
    Citagora\Model\DocumentView\Citation,
    Citagora\Model\DocumentView\CitationCollection,
    Citagora\Model\DocumentView\Contributor,
    Citagora\Model\DocumentView\ContributorCollection,
    Citagora\Model\DocumentView\Meta,
    Citagora\Model\DocumentView\Ratings,
    Citagora\Model\DocumentView\SocialMetrics;


class DummyDocuments extends Documents
{
    /**
     * @var array
     */
    private $dummyRecs = array();

    /**
     * @var int  Dummy IDs for dummy records
     */
    private $dummyId = 1000;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param string $dummyJsonFilePath
     */
    public function __construct($dummyJsonFilePath)
    {
        if ( ! is_readable($dummyJsonFilePath)) {
            throw new \RuntimeException("Cannot read dummy records from path: " . $dummyJsonFilePath);
        }

        $rawData  = file_get_contents($dummyJsonFilePath);
        $jsonData = json_decode($rawData);

        if ( ! $jsonData) {
            throw new \RuntimeException("Error parsing dummy records from path: " . $dummyJsonFilePath);
        }

        $this->dummyRecs = $jsonData;
    }

    // --------------------------------------------------------------

    /**
     * Get a single dummy document
     *
     * @param int $identifier
     */
    public function getDocument($identifer)
    {
        $key = array_rand($this->dummyRecs);
        return $this->buildDummyRec($this->dummyRecs[$key]);
    }

    // --------------------------------------------------------------

    /**
     * Get dummy records
     *
     * @param int     $limit   0 for no limit
     * @param boolean $random  Random set of records?
     */
    public function getDocuments($limit = 0, $random = true)
    {
        $recs = $this->dummyRecs;

        //Recs
        if ($random) {
            shuffle($recs);
        }

        if ($limit > 0) {
            $recs = (count($recs > $limit))
                ? array_slice($recs, 0, $limit)
                : $recs;
        }

        return array_map(array($this, 'buildDummyRec'), $recs);
    }

    // --------------------------------------------------------------

    /**
     * Build a dummy record from its dummy input specification (from JSON)
     *
     * @param stdclass $dummyInput
     * @return Citagora\Model\DocumentView\Document
     */
    public function buildDummyRec($dummyInput)
    {
        // header("Content-type: text/plain");
        // var_dump($dummyInput);

        $basicMappings = array(
            'title'      => 'title',
            'pmid'       => 'pmid',
            'year'       => 'year',
            'journal'    => 'journalTitle',
            'isbn'       => 'isbn',
            'pagination' => 'pages',
            'abstract'   => 'abstract',
            'url'        => 'url',
            'doi'        => 'doi'
        );

        //Build new document
        $document = new Document();
        $document->identifier = $this->getDummyId();

        //Basic Mappings
        foreach($basicMappings as $dest => $src) {
            if (isset($dummyInput->$src)) {
                $document->$dest = $dummyInput->$src;
            }
        }

        //Contributors
        $document->contributors = new ContributorCollection();
        foreach($dummyInput->authorList as $name) {

            $contributor = new Contributor();
            $contributor->surname = $name;

            $document->contributors->add($contributor);
        }

        //Dummy Meta
        $document->meta = new Meta();
        $document->meta->sources = array('Dummy Recs' => 'http://example.com');

        //Dummy Ratings
        $document->ratings = new Ratings();
        $document->ratings->overall     = rand(1, 5) + (rand(1, 100) / 100);
        $document->ratings->readability = rand(1, 5) + (rand(1, 100) / 100);
        $document->ratings->accuracy    = rand(1, 5) + (rand(1, 100) / 100);
        $document->ratings->orginiality = rand(1, 5) + (rand(1, 100) / 100);
        $document->ratings->totalCount  = rand(0, 20);

        //Dummy Social Metrics
        $document->socialMetrics = new SocialMetrics();
        $document->socialMetrics->numTweets    = rand(1, 100);
        $document->socialMetrics->numMendeley  = rand(1, 100);
        $document->socialMetrics->numCiteulike = rand(1, 100);
        $document->socialMetrics->numConnotea  = rand(1, 100);
        $document->socialMetrics->numDisqus    = rand(1, 100);

        //Citations
        $document->citations = new CitationCollection();
        $numberOfCitations = rand(1, count($this->dummyRecs));
        $tempDocs = $this->dummyRecs;
        shuffle($tempDocs);
        foreach(array_slice($tempDocs,0, $numberOfCitations) as $dummyDoc) {
            $citationDoc = new Document();
            $citationDoc->title = $dummyDoc->title;
            $citationDoc->contributors = new ContributorCollection();
            foreach($dummyDoc->authorList as $name) {
                $contributor = new Contributor();
                $contributor->surname = $name;
                $citationDoc->contributors->add($contributor);
            }
            $document->citations->add($citationDoc);     
        }



        return $document;
    }

    // --------------------------------------------------------------

    /**
     * Return a dummy identifier
     *
     * @return int
     */
    private function getDummyId()
    {
        $this->dummyId++;
        return $this->dummyId;
    }
}

/* EOF: DummyRecords.php */