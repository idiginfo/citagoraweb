<?php

namespace Citagora\Model\DocumentView;
use Citagora\Model\AbstractCollectionObject;

class ContributorCollection extends AbstractCollectionObject
{
    // --------------------------------------------------------------

    public function add(Contributor $value)
    {
        parent::add($value);
    }

    // --------------------------------------------------------------

    public function set($key, Contributor $value)
    {
        parent::set($key, $value);
    }
}

/* EOF: ContributorCollection.php */