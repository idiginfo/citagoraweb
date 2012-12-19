<?php

namespace Citagora\Entity\Document;
use Citagora\EntityManager\Entity;
use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/**
 * @ODM\Document
 */
class Document extends Entity
{
    /**
     * @var int
     * @ODM\Id     
     */
    protected $identifier;

    /**
     * @var string
     * @ODM\String     
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
    protected $pmid;

    /**
     * @var string
     * @ODM\String       
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
     * @ODM\EmbedOne(
     *    targetDocument="Citation"
     * )
     */
    protected $citations;

    /**
     * @var SocialMetrics
     * @ODM\Embed
     */
    protected $socialMetrics;

    /**
     * @var Meta
     * @ODM\Meta
     */
    protected $meta;

    // --------------------------------------------------------------

    public function __construct()
    {
        $this->contributors = new ArrayCollection();
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