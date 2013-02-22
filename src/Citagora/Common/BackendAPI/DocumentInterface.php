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