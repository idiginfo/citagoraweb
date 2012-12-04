<?php

namespace Citagora\Service;

/**
 * Documents Service Provider handles all requests for documents
 */
class Documents
{
    /**
     * @return Citagora\Model\DocumentView\Document
     */
    public function getDocument($identifier)
    {
        //...
    }

    /**
     * @return array (change to Collection object?)
     */
    public function getDocuments($limit = 0)
    {
        //...
    }
}

/* EOF: Documents.php */