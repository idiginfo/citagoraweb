<?php

namespace Citagora\Model\DocumentView;
use Citagora\Model\AbstractValueObject;

class Citation extends AbstractValueObject
{
    /**
     * @var string
     */
    protected $title;

    /**
     * @var ContributorCollection
     */
    protected $contributors;

    /**
     * @var string
     */
    protected $url;
}

/* EOF: Citation.php */