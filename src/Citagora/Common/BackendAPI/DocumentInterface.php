<?php

namespace Citagora\Common\BackendAPI;

/**
 * Document API Interface
 */
interface DocumentInterface
{
    /**
     * @return Document|null  A single document or NULL if not exists
     */
    function getDocument($id);

    /**
     * @return array  Document objects (empty array for no results)
     */
    function getDocumentsById(array $ids);

    /**
     * Get documents for indexation
     *
     * @param  mixed  $startPosition  Some indicator of where to start for maintaining index state
     * @param  int    $limit          Number of documents to retrieve at a time
     * @param  int    $offset         offset, relative to start indicator
     * @return array  Document objects
     */
    function getDocumentsForIndexing($startPosition = null, $limit = null, $offset = null);

    /**
     * Get recent documents mainly for display on front-page
     *
     * @return array  Document objects
     */
    function getRecentDocuments($limit = null, $offset = null);

    /**
     * Search documents and get identifiers back
     *
     * @return array  Array of document identifiers
     */
    function search($query);
}

/* EOF: DocumentInterface.php */