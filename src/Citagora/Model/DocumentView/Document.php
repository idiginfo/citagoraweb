<?php

namespace Citagora\Model\DocumentView;
use Citagora\Model\AbstractValueObject;

class Document extends AbstractValueObject
{
    /**
     * @var string
     */
    private $title;

    /**
     * @var Citagora\DocumentView\ContributorCollection
     */
    private $contributors;

    /**
     * @var string
     */
    private $journal;

    /**
     * @var string
     */
    private $publicationType;

    /**
     * @var int
     */
    private $year;

    /**
     * @var string
     */
    private $pagination;

    /**
     * @var string
     */
    private $doi;

    /**
     * @var string
     */
    private $pmid;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $abstract;

    /**
     * @var Citagora\DocumentView\Ratings
     */
    private $ratings;

    /**
     * @var Citagora\DocumentView\CitationCollection
     */
    private $citations;

    /**
     * @var Citagora\DocumentView\SocialMetrics
     */
    private $socialMetrics;

    // --------------------------------------------------------------

    public function __get($item)
    {
        return $this->$item;
    }

    // --------------------------------------------------------------

    public function __set($item, $value)
    {
        $this->$item = $value;
    }
}

/* EOF: Document.php */