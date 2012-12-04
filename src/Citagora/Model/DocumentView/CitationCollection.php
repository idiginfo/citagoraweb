<?php

namespace Citagora\Model\DocumentView;
use Citagora\Model\AbstractCollectionObject;

class CitationCollection extends AbstractCollectionObject
{
    // --------------------------------------------------------------

    public function add(Document $value)
    {
        parent::add($value);
    }

    // --------------------------------------------------------------

    public function set($key, Document $value)
    {
        parent::set($key, $value);
    }
}

/* EOF: CitationCollection.php */