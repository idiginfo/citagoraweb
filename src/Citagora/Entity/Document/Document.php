<?php

namespace Citagora\Entity\Document;

use Citagora\EntityManager\Entity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ODM\Document
 */
class Document extends Entity
{
    /**
     * @var int
     * @ODM\Id     
     */
    protected $id;

    /**
     * @var string
     * @ODM\String    
     */
    protected $title;

    /**
     * @var string
     * @ODM\String
     * @ODM\Index
     */
    protected $normalizedTitle;

    /**
     * @var string
     * @ODM\String       
     */
    protected $journal;

    /**
     * @var string
     * @ODM\String       
     */
    protected $publicationType;

    /**
     * @var int
     * @ODM\Int     
     */
    protected $year;

    /**
     * @var DateTime
     * @ODM\Date
     */
    protected $pubDate;

    /**
     * @var string
     * @ODM\String       
     */
    protected $pagination;

    /**
     * @var string
     * @ODM\String
     * @ODM\UniqueIndex
     */
    protected $doi;

    /**
     * @var string
     * @ODM\String       
     */
    protected $isbn;

    /**
     * @var string
     * @ODM\String
     */
    protected $issn;

    /**
     * @var string
     * @ODM\String
     * @ODM\UniqueIndex
     */
    protected $pmid;

    /**
     * @var string
     * @ODM\String  
     * @ODM\UniqueIndex
     */
    protected $url;

    /**
     * @var string
     * @ODM\String            
     */
    protected $abstract;

    /**
     * @var ArrayCollection
     * @ODM\EmbedMany(
     *    targetDocument="Rating"
     * )
     */
    protected $ratings;

    /**
     * @var ArrayCollection
     * @ODM\ReferenceMany(
     *     targetDocument="Contributor",
     *     cascade={"persist","refresh","merge"}
     * )
     */
    protected $contributors;

    /**
     * @var ArrayCollection
     * @ODM\EmbedMany(
     *    targetDocument="Citation"
     * )
     */
    protected $citations;

    /**
     * @var SocialMetrics
     * @ODM\EmbedOne(
     *    targetDocument="SocialMetrics"
     * )
     */
    protected $socialMetrics;

    /**
     * @var Meta
     * @ODM\EmbedOne(
     *    targetDocument="Meta"
     * )
     */
    protected $meta;

    /**
     * @var array
     * @ODM\Collection
     */
    protected $keywords;

    // --------------------------------------------------------------

    public function __construct()
    {
        //Initialize Everything
        $this->keywords      = array();
        $this->contributors  = new ArrayCollection();
        $this->citations     = new ArrayCollection();
        $this->ratings       = new ArrayCollection();
        $this->meta          = new Meta();
        $this->socialMetrics = new SocialMetrics();
    }

    // --------------------------------------------------------------

    public function __set($item, $value)
    {
        switch ($item) {
            case 'contributors':
            case 'citations':
            case 'normalizedTitle':
            case 'ratings':
                throw new \Exception("Cannot modify {%item} property directly");
            break;
            case 'title':
                $this->setNormalizedTitle($value);
            break;
        }

        parent::__set($item, $value);
    }

    // --------------------------------------------------------------

    public function addRating(Rating $rating)
    {
        $this->ratings->add($rating);
    }

    // --------------------------------------------------------------

    public function addContributor(Contributor $contributor)
    {
        $this->contributors->add($contributor);
    }

    // --------------------------------------------------------------

    public function addCitation(Citation $citation)
    {
        $this->citations->add($citation);
    }

    // --------------------------------------------------------------

    public function addKeyword($keyword)
    {
        if ( ! in_array($keyword, $this->keywords)) {
            $this->keywords[] = $keyword;
        }
    }

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

    // --------------------------------------------------------------

    /**
     * Add a normalized title, too
     *
     * @param string $fullTitle
     */
    private function setNormalizedTitle($fullTitle)
    {
        //Strip out punctuation and special characters
        $this->normalizedTitle = strtolower(preg_replace("/[^a-zA-Z 0-9]+/", " ", $fullTitle));
    }    
}

/* EOF: Document.php */