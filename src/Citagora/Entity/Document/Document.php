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
     * @ODM\Index     
     */
    protected $title;

    /**
     * @var ArrayCollection
     * @ODM\ReferenceMany(
     *     targetDocument="Contributor",
     *     cascade={"persist","refresh","merge"}
     * )
     */
    protected $contributors;

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
     * @var Ratings
     * @ODM\EmbedOne(
     *    targetDocument="Ratings"
     * )
     */
    protected $ratings;

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
        $this->meta          = new Meta();
        $this->socialMetrics = new SocialMetrics();
        $this->ratings       = new Ratings();
    }

    // --------------------------------------------------------------

    public function __set($item, $value)
    {
        switch ($item) {
            case 'contributors':
            case 'citations':
                throw new \Exception("Cannot modify contributors or citations properties directly");
            break;
        }

        parent::__set($item, $value);
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
}

/* EOF: Document.php */