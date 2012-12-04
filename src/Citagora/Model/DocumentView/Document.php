<?php

namespace Citagora\Model\DocumentView;
use Citagora\Model\AbstractValueObject;

class Document extends AbstractValueObject
{
    /**
     * @var int
     */
    protected $identifier;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var Citagora\Model\DocumentView\ContributorCollection
     */
    protected $contributors;

    /**
     * @var string
     */
    protected $journal;

    /**
     * @var string
     */
    protected $publicationType;

    /**
     * @var int
     */
    protected $year;

    /**
     * @var string
     */
    protected $pagination;

    /**
     * @var string
     */
    protected $doi;

    /**
     * @var string
     */
    protected $isbn;

    /**
     * @var string
     */
    protected $pmid;

    /**
     * @var string
     */
    protected $url;

    /**
     * @var string
     */
    protected $abstract;

    /**
     * @var Citagora\Model\DocumentView\Ratings
     */
    protected $ratings;

    /**
     * @var Citagora\Model\DocumentView\CitationCollection
     */
    protected $citations;

    /**
     * @var Citagora\Model\DocumentView\SocialMetrics
     */
    protected $socialMetrics;

    /**
     * @var Citagora\Model\DocumentView\Meta
     */
    protected $meta;

    // --------------------------------------------------------------

    public function doiUrl()
    {
        if ($this->doi) {

            if (preg_match("/^http[s]?:\/\//i", $this->doi)) {
                return $this->doi;
            }
            else {
                return 'http://dx.doi.org/' . $this->doi;
            }

        }
        else {
            return null;
        }
    }    

    // --------------------------------------------------------------

    public function pmidUrl()
    {
        return ($this->pmid)
            ? 'http://www.ncbi.nlm.nih.gov/pubmed/' . $this->pmid
            : null;
    }    
}

/* EOF: Document.php */