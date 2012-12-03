<?php

namespace Citagora\Model;

class PaperSearchRequest
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

/* EOF: PaperSearchRequest.php */