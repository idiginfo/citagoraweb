<?php

namespace Citagora\Model\DocumentView;
use Citagora\Model\AbstractCollectionObject;

class CitationCollection extends ArrayCollection
{
    // --------------------------------------------------------------

    public function add(Citatation $value)
    {
        parent::add($value);
    }

    // --------------------------------------------------------------

    public function set($key, Citatation $value)
    {
        parent::set($key, $value);
    }
}

/* EOF: CitationCollection.php */