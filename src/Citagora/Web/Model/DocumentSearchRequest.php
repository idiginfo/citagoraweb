<?php

namespace Citagora\Web\Model;

/**
 * Document Search Request Model
 *
 * Possible patterns:
 *   . doi regex   - convert to faceted doi
 *   . url         - convert to facted url
 *   . solr format - pass along as solr query
 *   . plaintext   - conver to general solr query
 */
class DocumentSearchRequest
{
    private $query;

    // --------------------------------------------------------------

    public function setQuery($query)
    {
        $this->query = $query;
    }

    // --------------------------------------------------------------

    public function getQuery()
    {
        return $this->query;
    }
}

/* EOF: DocumentSearchRequest.php */