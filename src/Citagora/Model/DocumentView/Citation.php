<?php

namespace Citagora\Model\DocumentView;
use Citagora\Model\AbstractValueObject;

class Citation extends AbstractValueObject
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var ContributorCollection
     */
    private $contributors;

    /**
     * @var string
     */
    private $url;
}

/* EOF: Citation.php */