<?php

namesapce Citagora\Common\BackendAPI;

use Citagora\Common\Model\Factory;
use Citagora\Common\DataSource\MySQL\Client as MySQLClient;

/**
 * Default Document API -- Provides an abstract interface to documents
 *
 * Uses Doctrine DBAL to connect to Greg's database
 */
class DocumentAPI implements DocumentInterface
{
    /**
     * @var Citagora\Common\DataSource\MySQL\Client
     */
    private $mysqlClient;

    // --------------------------------------------------------------

    /**
     * Constructor
     *
     * @param Citagora\Common\DataSource\MySQL\Client
     */
    public function __construct(MySQLClient $mysql)
    {
        $this->mysqlClient = $mysql;
    }

    // --------------------------------------------------------------

    /**
     * @return Document  A single document
     */
    public function getDocument($id)
    {
        //Get the document from the client
    }

    // --------------------------------------------------------------

    /**
     * @return array  Document objects
     */
    public function getDocumentsById(array $ids)
    {
        //Get the documents from the client
    }

    // --------------------------------------------------------------

    /**
     * Get documents for indexation
     *
     * @param  mixed  $startPosition  Some indicator of where to start for maintaining index state
     * @param  int    $limit          Number of documents to retrieve at a time
     * @param  int    $offset         offset, relative to start indicator
     * @return array  Document objects
     */
    function getDocumentsForIndexing($startPosition = null, $limit = null, $offset = null)
    {
        //Get the documents from the MySQL client
    }

    // --------------------------------------------------------------

    /**
     * Get recent documents mainly for display on front-page
     *
     * @return array  Document objects
     */
    function getRecentDocuments($limit = null, $offset = null)
    {
        
    }

    // --------------------------------------------------------------

    /**
     * Search documents and get identifiers back
     *
     * @return array  Array of document identifiers
     */
    function search($query);
}

/* EOF: Document.php */