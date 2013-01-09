<?php

namespace Citagora\Web\Model;

/**
 * Document Search Request Model
 */
class DocumentSearchRequest
{
    private $query;

    // --------------------------------------------------------------

    public function setQuery($query)
    {
        $this->query = $query;
    }

    public function getQuery()
    {
        return $this->query;
    }
}

/* EOF: DocumentSearchRequest.php */