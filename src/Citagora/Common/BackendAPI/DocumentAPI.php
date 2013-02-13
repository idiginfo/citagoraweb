<?php

namesapce Citagora\Common\BackendAPI;

use Citagora\Common\Model\Factory;

/**
 * Document API
 *
 * Uses Doctrine DBAL to connect to Greg's database
 */
class DocumentAPI implements DocumentInterface
{
    /**
     * @var Factory
     */
    private $modelFactory;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Factory
     */
    public function __construct(Factory $modelFactory)
    {
        $this->modelFactory = $modelFactory;
    }

    // --------------------------------------------------------------

    /**
     * @return Document  A single document
     */
    function getDocument($id)
    {
        
    }

    // --------------------------------------------------------------

    /**
     * @return array  Document objects
     */
    function getDocumentsById(array $ids);

    // --------------------------------------------------------------

    /**
     * Get documents for indexation
     *
     * @param  mixed  $startPosition  Some indicator of where to start for maintaining index state
     * @param  int    $limit          Number of documents to retrieve at a time
     * @param  int    $offset         offset, relative to start indicator
     * @return array  Document objects
     */
    function getDocumentsForIndexing($startPosition = null, $limit = null, $offset = null);

    // --------------------------------------------------------------

    /**
     * Get recent documents mainly for display on front-page
     *
     * @return array  Document objects
     */
    function getRecentDocuments($limit = null, $offset = null);

    // --------------------------------------------------------------

    /**
     * Search documents and get identifiers back
     *
     * @return array  Array of document identifiers
     */
    function search($query);
}

/* EOF: Document.php */